<?php

namespace Zorbus\BlockBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zorbus\BlockBundle\DependencyInjection\Compiler\BlockCompilerConfig;
use Symfony\Component\Translation\TranslatorInterface;

class BlockCategoryType extends AbstractType
{

    protected $categories, $translator;

    public function __construct(BlockCompilerConfig $config, TranslatorInterface $translator)
    {
        $this->categories = $config->getCategories();
        $this->translator = $translator;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $categories = array();

        foreach ($this->categories as $category)
        {
            $categories[$category] = $this->translator->trans($category, array(), 'ZorbusBlockBundle');
        }
        
        $resolver->setDefaults(array(
            'choices' => $categories
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