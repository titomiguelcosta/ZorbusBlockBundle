<?php

namespace Zorbus\BlockBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zorbus\BlockBundle\DependencyInjection\Compiler\BlockCompilerPass;

class ZorbusBlockBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new BlockCompilerPass());
    }
}
