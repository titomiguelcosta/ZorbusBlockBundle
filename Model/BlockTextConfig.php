<?php

namespace Zorbus\BlockBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Zorbus\BlockBundle\Entity\Block as BlockEntity;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Form\FormFactory;

class BlockTextConfig extends BlockConfig
{
    public function __construct(AdminInterface $admin, TwigEngine $template, FormFactory $formFactory)
    {
        parent::__construct('zorbus_block.service.text', 'Text Block', $admin, $formFactory);
        $this->enabled = true;
        $this->themes = array('ZorbusBlockBundle:Render:text_default.html.twig' => 'Default', 'ZorbusBlockBundle:Render:text_no_title.html.twig' => 'No title');
        $this->template = $template;
    }

    public function getFormMapper()
    {
        return $this->formMapper
                ->add('title', 'text', array('constraints' => array(
                        new Assert\NotBlank(),
                        )))
                ->add('content', 'textarea', array(
                    'required' => false,
                    'attr' => array('class' => 'ckeditor'),
                    'constraints' => array(
                        new Assert\NotBlank(),
                        ), ))
                ->add('name', 'text', array(
                    'required' => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                ))
                ->add('lang', 'language', array('preferred_choices' => array('pt_PT', 'en')))
                ->add('theme', 'choice', array(
                    'choices' => $this->getThemes(),
                    'attr' => array('class' => 'span5 select2'),
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                ))
                ->add('cache_ttl', 'integer', array(
                    'required' => false,
                    'attr' => array('class' => 'span2'),
                    'constraints' => new Assert\Length(array('min' => 0)),
                ))
                ->add('enabled', 'checkbox', array('required' => false))
                ;
    }

    public function getBlockEntity(array $data, BlockEntity $block = null)
    {
        $block = null === $block ? new BlockEntity() : $block;

        $block->setService($this->getService());
        $block->setCategory('Text');
        $block->setParameters(json_encode(array('title' => $data['title'], 'content' => $data['content'])));
        $block->setEnabled((boolean) $data['enabled']);
        $block->setLang($data['lang']);
        $block->setName($data['name']);
        $block->setTheme($data['theme']);
        $block->setCacheTtl($data['cache_ttl']);

        return $block;
    }

    public function render(BlockEntity $block, $page = null, $request = null)
    {
        if ($block->getService() != $this->getService()) {
            throw new \InvalidArgumentException('Block service not supported');
        }

        $parameters = json_decode($block->getParameters());
        $title = $parameters->title;
        $content = $parameters->content;

        return $this->template->renderResponse($block->getTheme(), array('block' => $block, 'title' => $title, 'content' => $content));
    }
}
