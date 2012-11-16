<?php

namespace Zorbus\BlockBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class BlockAdmin extends Admin
{
    public function configure()
    {
    }
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('name')
                ->add('category')
                ->add('enabled')
        ;
    }
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('name')
                ->add('category', 'trans', array('catalogue' => 'ZorbusBlockBundle'))
                ->add('enabled')
        ;
    }

    public function configureShowFields(ShowMapper $filter)
    {
        $filter
                ->add('name')
                ->add('title')
                ->add('lang')
                ->add('category', 'trans', array('catalogue' => 'ZorbusBlockBundle'))
                ->add('service')
                ->add('parameters')
                ->add('enabled')
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('zorbus_block_config', 'config/{service}', array('_controller' => 'ZorbusBlockBundle:Admin\Block:configBlock'));
        $collection->add('zorbus_block_show', 'config/{id}/show', array('_controller' => 'ZorbusBlockBundle:Admin\Block:showBlock'));
        $collection->add('zorbus_block_models_list', 'models/list', array('_controller' => 'ZorbusBlockBundle:Admin\Block:listModels'));
    }

}