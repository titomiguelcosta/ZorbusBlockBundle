<?php

namespace Zorbus\BlockBundle\DependencyInjection\Compiler;

use Zorbus\BlockBundle\Model\BlockConfig;

class BlockCompilerConfig
{

    protected $models = array();

    public function addModel(BlockConfig $object, $category = 'None')
    {
        $this->models[$category][] = $object;
    }

    public function getModels()
    {
        ksort($this->models);
        return $this->models;
    }

}