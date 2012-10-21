<?php

namespace Zorbus\BlockBundle\Model;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormFactory;
use Zorbus\BlockBundle\Entity\Block;

class BlockRssConfig extends BlockConfig
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
                ->add('url', 'text', array('constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Url()
                )))
                ->add('lang', 'text')
                ->add('name', 'text')
                ->add('is_enabled', 'checkbox');
    }
    public function getBlockEntity(array $data, Block $block = null)
    {
        $block = null === $block ? new Block() : $block;

        $block->setType('rss');
        $block->setConfiguration(json_encode(array('title' => $data['title'], 'url' => $data['url'])));
        $block->setIsEnabled((boolean) $data['is_enabled']);
        $block->setLang($data['lang']);
        $block->setName($data['name']);

        return $block;
    }

}