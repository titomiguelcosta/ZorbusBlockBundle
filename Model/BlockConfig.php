<?php

namespace Zorbus\BlockBundle\Model;

use Symfony\Component\Form\FormFactory;
use Zorbus\BlockBundle\Entity\Block as BlockEntity;
use Sonata\BlockBundle\Model\Block as BlockModel;
use Sonata\AdminBundle\Admin\AdminInterface;

abstract class BlockConfig
{

    protected $enabled = true;
    protected $service;
    protected $name = null;
    protected $themes = array();
    protected $admin;

    public function __toString()
    {
        return $this->getName();
    }

    public function __construct($service, $name = null, AdminInterface $admin = null, FormFactory $formFactory = null)
    {
        $this->service = $service;
        $this->name = $name === null ? $service : $name;
        $this->admin = $admin;
        $this->formBuilder = null !== $formFactory ? $formFactory->createBuilder() : null;
    }

    public function getThemes()
    {
        return $this->themes;
    }

    public function setTheme($identifier, $name)
    {
        $this->themes[$identifier] = $name;
    }

    public function getService()
    {
        return $this->service;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEnabled($status)
    {
        $this->enabled = (bool) $status;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     *
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    public function getModel(BlockEntity $block)
    {
        $model = new BlockModel();
        $settings = (array) json_decode($block->getParameters());
        $model->setSettings($settings);

        return $model;
    }

    abstract public function getFormBuilder();

    abstract public function getBlockEntity(array $data, BlockEntity $block = null);

    abstract public function render(BlockEntity $block);
}