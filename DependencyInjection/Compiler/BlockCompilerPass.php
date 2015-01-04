<?php

namespace Zorbus\BlockBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class BlockCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('zorbus_block.compiler.config')) {
            return;
        }

        $definition = $container->getDefinition(
                'zorbus_block.compiler.config'
        );

        $taggedServices = $container->findTaggedServiceIds(
                'zorbus_block.block'
        );
        foreach ($taggedServices as $id => $attributes) {
            foreach ($attributes as $attribute) {
                $category = array_key_exists('category', $attribute) ? $attribute['category'] : 'Categories';

                $definition->addMethodCall(
                        'addModel', array(new Reference($id), $category)
                );
                $definition->addMethodCall(
                        'addCategory', array($category)
                );
            }
        }
    }
}
