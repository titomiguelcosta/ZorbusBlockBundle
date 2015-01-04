<?php

namespace Zorbus\BlockBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Controller\CRUDController;
use Zorbus\BlockBundle\Entity\Block as BlockEntity;

class BlockController extends CRUDController
{
    public function createAction()
    {
        $request = $this->get('request_stack')->getMasterRequest();
        if ($request->isMethod('get')) {
            return $this->redirect($this->admin->generateUrl('zorbus_block_models_list'));
        }

        $data = $request->request->get('form', array());

        return $this->configBlockAction($data['service'], $request);
    }
    public function listModelsAction()
    {
        $compiler = $this->get('zorbus_block.compiler.config');

        return $this->render('ZorbusBlockBundle:Admin:models.html.twig', array('models' => $compiler->getModels()));
    }
    public function configBlockAction($service, Request $request)
    {
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $config = $this->get($service);
        /* @var $form \Symfony\Component\Form\Form */
        $form = $config->getFormMapper()->add('service', 'hidden')->getFormBuilder()->getForm();
        $form->setData(array('service' => $config->getService()));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $block = $config->getBlockEntity($form->getData());
                $em = $this->getDoctrine()->getManager();
                $em->persist($block);
                $em->flush();

                return $this->redirect($this->admin->generateObjectUrl('edit', $block));
            }
        }
        $view = $form->createView();

        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        //return $this->render('ZorbusBlockBundle:Admin:config.html.twig', array('form' => $view, 'object' => $this->admin->getNewInstance()));
        return $this->render($this->admin->getTemplate('edit'), array(
            'action' => 'create',
            'form'   => $view,
            'object' => new BlockEntity(),
        ));
    }
    public function showAction($id = null)
    {
        $request = $this->get('request_stack')->getMasterRequest();

        $id = $request->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('VIEW', $object)) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        $config = $this->get($object->getService());
        $output = $config->render($object);

        return $this->render('ZorbusBlockBundle:Admin:show.html.twig', array(
            'action'   => 'show',
            'object'   => $object,
            'elements' => $this->admin->getShow(),
            'output'   => $output->getContent(),
        ));
    }
    public function editAction($id = null)
    {
        $request = $this->get('request_stack')->getMasterRequest();

        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('EDIT', $object)) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        //$form = $this->admin->getForm();
        /******
         * Rewrite
         */
        $config = $this->get($object->getService());
        $form = $config->getFormMapper()->add('service', 'hidden')->getFormBuilder()->getForm();

        $form->setData($object->toArray());

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $isFormValid = $form->isValid();

             // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                //$this->admin->update($object);
                $object = $config->getBlockEntity($form->getData(), $object);
                $this->admin->update($object);

                $this->get('session')->getFlashBag()->add('sonata_flash_success', 'flash_edit_success');

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result'    => 'ok',
                        'objectId'  => $this->admin->getNormalizedIdentifier($object),
                    ));
                }

                // redirect to edit mode
                return $this->redirectTo($object);
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                $this->get('session')->getFlashBag()->add('sonata_flash_error', 'flash_edit_error');
            } elseif ($this->isPreviewRequested()) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'edit',
            'form'   => $view,
            'object' => $object,
        ));
    }
    /**
     * return the Response object associated to the list action
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     *
     * @return Response
     */
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render('ZorbusBlockBundle:Admin:list.html.twig', array(
            'action'   => 'list',
            'form'     => $formView,
            'datagrid' => $datagrid,
        ));
    }
}
