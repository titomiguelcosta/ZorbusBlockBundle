<?php

namespace Zorbus\BlockBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Zorbus\BlockBundle\DependencyInjection\Compiler\BlockCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ZorbusBlockBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new BlockCompilerPass());
    }
}
