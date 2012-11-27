<?php

namespace Zorbus\BlockBundle\DependencyInjection\Compiler;

use Zorbus\BlockBundle\Model\BlockConfig;

class BlockCompilerConfig
{

    protected $models = array();
    protected $categories = array();

    public function addModel(BlockConfig $object, $category = 'None')
    {
        $this->models[$category][] = $object;
    }

    public function addCategory($category)
    {
        $this->categories[$category] = $category;
    }

    public function getModels()
    {
        ksort($this->models);
        return $this->models;
    }

    public function getCategories()
    {
        ksort($this->categories);
        return $this->categories;
    }

}