<?php
namespace Zorbus\BlockBundle\Model;
use Symfony\Component\Form\FormFactory;
use Zorbus\BlockBundle\Entity\Block;
use Sonata\BlockBundle\Model\Block as BlockModel;

abstract class BlockConfig {
    protected $enabled = true;
    protected $type;
    protected $name = null;
    protected $themes = array();
    protected $formFactory;

    public function __toString()
    {
        return $this->getName();
    }
    public function __construct($type, $name = null, FormFactory $formFactory = null)
    {
        $this->type = $type;
        $this->name = $name === null ? $type : $name;
        $this->formFactory = $formFactory;
    }
    public function getThemes()
    {
        return $this->themes;
    }
    public function setTheme($identifier, $name)
    {
        $this->themes[$identifier] = $name;
    }
    public function getType()
    {
        return $this->type;
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
    public function getModel(Block $block)
    {
        $model = new BlockModel();
        $settings = (array) json_decode($block->getParameters());
        $model->setSettings($settings);

        return $model;
    }
    abstract public function getFormBuilder();
    abstract public function getBlockEntity(array $data, Block $block = null);
}