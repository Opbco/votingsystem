<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Batch;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class BatchAdmin extends AbstractAdmin
{

    public function toString(object $object): string
    {
        return $object instanceof Batch
            ? $object->getName()
            : 'Batch'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('code')
            ->add('name');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('code')
            ->add('name')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
                'row_align' => 'right'
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('code')
            ->add('name');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('code')
            ->add('name');
    }
}
