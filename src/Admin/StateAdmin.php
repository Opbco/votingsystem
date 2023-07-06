<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\State;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Show\ShowMapper;

final class StateAdmin extends AbstractAdmin
{

    public function toString(object $object): string
    {
        return $object instanceof State
            ? $object->getName()
            : 'State'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('country', null, ['label'=>'Country'])
            ->add('country.continent', null, ['label'=>'Continent'])
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name')
            ->add('country', null, ['label'=>'Country'])
            ->add('country.continent', null, ['label'=>'Continent'])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name')
            ->add('country', ModelAutocompleteType::class, [
                'property' => 'name'
            ])
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('country.name', null, ['label'=>'Country'])
            ->add('country.continent', null, ['label'=>'Continent'])
            ;
    }
}
