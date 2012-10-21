<?php

namespace Zorbus\BlockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Zorbus\BlockBundle\Entity\Block;

class DefaultController extends Controller
{
    public function configAction($type, Request $request)
    {
        $config = $this->get(sprintf('zorbus.block.config.%s', $type));
        $form = $config->getFormBuilder()->getForm();

        if ($request->isMethod('POST')){
            $form->bind($request);

            if ($form->isValid())
            {
                $block = $config->getBlockEntity($form->getData());
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($block);
                $em->flush();

                return $this->redirect($this->generateUrl('zorbus_block_show', array('id' => $block->getId())));
            }
        }
        return $this->render('ZorbusBlockBundle:Default:config.html.twig', array('form' => $form->createView()));
    }
    public function showAction($id, Request $request)
    {
        $block = $this->getDoctrine()->getRepository('ZorbusBlockBundle:Block')->findOneBy(array('id' => $id));

        $output = '';
        if ($block)
        {
            $config = $this->get(sprintf('zorbus.block.config.%s', $block->getType()));
            $service = $this->get(sprintf('sonata.block.service.%s', $block->getType()));
            $output = $service->execute($config->getModel($block))->getContent();
        }
        return $this->render('ZorbusBlockBundle:Default:show.html.twig', array('block' => $block, 'output' => $output));
    }
}
