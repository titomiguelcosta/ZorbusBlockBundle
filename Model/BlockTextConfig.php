<?php

namespace Zorbus\BlockBundle\Model;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormFactory;
use Zorbus\BlockBundle\Entity\Block;

class BlockTextConfig extends BlockConfig
{

    public function __construct(FormFactory $formFactory)
    {
        parent::__construct('sonata.block.service.rss', 'RSS Feed Block', $formFactory);
        $this->enabled = true;
        $this->themes = array();
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
                ->add('name', 'text')
                ->add('enabled', 'checkbox');
    }
    public function getBlockEntity(array $data, Block $block = null)
    {
        $block = null === $block ? new Block() : $block;

        $block->setType('text');
        $block->setParameters(json_encode(array('title' => $data['title'], 'content' => $data['content'])));
        $block->setEnabled((boolean) $data['enabled']);
        $block->setLang($data['lang']);
        $block->setName($data['name']);

        return $block;
    }

}