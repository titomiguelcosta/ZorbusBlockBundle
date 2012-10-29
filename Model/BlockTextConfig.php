<?php

namespace Zorbus\BlockBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Zorbus\BlockBundle\Entity\Block as BlockEntity;
use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Form\FormFactory;

class BlockTextConfig extends BlockConfig
{

    public function __construct(AdminInterface $admin, TimedTwigEngine $template, FormFactory $formFactory)
    {
        parent::__construct('zorbus_block.service.text', 'Text Feed Block', $admin, $formFactory);
        $this->enabled = true;
        $this->themes = array();
        $this->template = $template;
    }

    public function getFormBuilder()
    {
        $formMapper = new FormMapper($this->admin->getFormContractor(), $this->formBuilder, $this->admin);
        $formMapper->add('title', 'text', array('constraints' => array(
                        new Assert\NotBlank()
                        )))
                ->add('content', 'textarea', array('constraints' => array(
                        new Assert\NotBlank()
                        )))
                ->add('lang', 'text')
                ->add('theme', 'choice', array('choices' => $this->getThemes()))
                ->add('name', 'text')
                ->add('enabled', 'checkbox', array('required' => false));
        foreach ($this->admin->getExtensions() as $extension) {
            $extension->configureFormFields($formMapper);
            die("aaaa");
        }

        return $formMapper->getFormBuilder();
    }

    public function getBlockEntity(array $data, BlockEntity $block = null)
    {
        $block = null === $block ? new BlockEntity() : $block;

        $block->setService($this->getService());
        $block->setParameters(json_encode(array('title' => $data['title'], 'content' => $data['content'])));
        $block->setEnabled((boolean) $data['enabled']);
        $block->setLang($data['lang']);
        $block->setName($data['name']);
        $block->setTheme($data['theme']);

        return $block;
    }

    public function getThemes()
    {
        return array('default' => 'Default', 'nice' => 'Beautiful');
    }

    public function render(BlockEntity $block)
    {
        if ($block->getService() != $this->getService())
        {
            throw new \InvalidArgumentException('Block service not supported');
        }

        $parameters = json_decode($block->getParameters());

        return $this->template->renderResponse('ZorbusBlockBundle:Render:text.html.twig', array('block' => $block, 'parameters' => $parameters));
    }

}