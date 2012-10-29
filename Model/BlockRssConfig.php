<?php

namespace Zorbus\BlockBundle\Model;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormFactory;
use Zorbus\BlockBundle\Entity\Block as BlockEntity;
use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;

class BlockRssConfig extends BlockConfig
{

    public function __construct(FormFactory $formFactory, TimedTwigEngine $template)
    {
        parent::__construct('zorbus_block.service.rss', 'RSS Feed Block', $formFactory);
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
                ->add('url', 'text', array('constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Url()
                )))
                ->add('lang', 'text')
                ->add('name', 'text')
                ->add('enabled', 'checkbox', array('required' => false));
    }
    public function getBlockEntity(array $data, BlockEntity $block = null)
    {
        $block = null === $block ? new BlockEntity() : $block;

        $block->setService($this->getService());
        $block->setParameters(json_encode(array('title' => $data['title'], 'url' => $data['url'])));
        $block->setEnabled((boolean) $data['enabled']);
        $block->setLang($data['lang']);
        $block->setName($data['name']);
        $block->setTheme($data['theme']);

        return $block;
    }
    public function render(BlockEntity $block)
    {
        parent::render($block);

        $parameters = json_decode($block->getParameters());

        $feeds = array();

        if ($parameters->url) {
            $options = array(
                'http' => array(
                    'user_agent' => 'Sonata/RSS Reader',
                    'timeout' => 2,
                )
            );

            // retrieve contents with a specific stream context to avoid php errors
            $content = @file_get_contents($parameters->url, false, stream_context_create($options));

            if ($content) {
                // generate a simple xml element
                try {
                    $feeds = new \SimpleXMLElement($content);
                    $feeds = $feeds->channel->item;
                } catch(\Exception $e) {
                    // silently fail error
                }
            }
        }

        return $this->template->renderResponse('ZorbusBlockBundle:Render:rss.html.twig', array('block' => $block, 'parameters' => $parameters, 'feeds' => $feeds));
    }

}