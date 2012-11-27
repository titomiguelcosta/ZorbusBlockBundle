<?php
namespace Zorbus\BlockBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zorbus\BlockBundle\DependencyInjection\Compiler\BlockCompilerConfig;


class BlockCategoryType extends AbstractType
{
    protected $categories;

    public function __construct(BlockCompilerConfig $config)
    {
        $this->categories = $config->getCategories();
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->categories
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'block_categories';
    }
}