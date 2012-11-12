<?php

namespace Zorbus\BlockBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class BlockAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('name')
                ->add('parameters')
                ->add('enabled', null, array('required' => false))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('name')
                ->add('category')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('name')
                ->add('category')
                ->add('enabled')
        ;
    }

    public function configureShowFields(ShowMapper $filter)
    {
        $filter
                ->add('name')
                ->add('title')
                ->add('category')
                ->add('service')
                ->add('parameters')
                ->add('enabled')
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
                ->with('name')
                ->assertMaxLength(array('limit' => 255))
                ->end()
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('zorbus_block_config', 'config/{service}', array('_controller' => 'ZorbusBlockBundle:Admin\Block:configBlock'));
        $collection->add('zorbus_block_show', 'config/{id}/show', array('_controller' => 'ZorbusBlockBundle:Admin\Block:showBlock'));
        $collection->add('zorbus_block_models_list', 'models/list', array('_controller' => 'ZorbusBlockBundle:Admin\Block:listModels'));
    }

}