<?php

namespace Zorbus\BlockBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormFactory;
use Zorbus\BlockBundle\Entity\Block as BlockEntity;
use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;

class BlockTextConfig extends BlockConfig
{

    public function __construct(FormFactory $formFactory, TimedTwigEngine $template)
    {
        parent::__construct('zorbus_block.service.text', 'Text Feed Block', $formFactory);
        $this->enabled = true;
        $this->themes = array();
        $this->template = $template;
    }

    public function getFormBuilder()
    {
        $formFactory = $this->getFormFactory();
        return $formFactory->createBuilder()
                        ->add('title', 'text', array('constraints' => array(
                                new Assert\NotBlank()
                                )))
                        ->add('content', 'textarea', array('constraints' => array(
                                new Assert\NotBlank()
                                )))
                        ->add('lang', 'text')
                        ->add('theme', 'choice', array('choices' => $this->getThemes()))
                        ->add('name', 'text')
                        ->add('enabled', 'checkbox', array('required' => false));
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