<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Departement;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Show\ShowMapper;

final class DepartementAdmin extends AbstractAdmin
{

    public function toString(object $object): string
    {
        return $object instanceof Departement
            ? $object->getName()
            : 'Division'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('state', null, ['label'=>'State'])
            ->add('state.country', null, ['label'=>'Country'])
            ->add('state.country.continent', null, ['label'=>'Continent'])
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name')
            ->add('state', null, ['label'=>'State'])
            ->add('state.country', null, ['label'=>'Country'])
            ->add('state.country.continent', null, ['label'=>'Continent'])
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
            ->add('state', ModelAutocompleteType::class, [
                'property' => 'name'
            ])
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('state', null, ['label'=>'State'])
            ->add('state.country', null, ['label'=>'Country'])
            ->add('state.country.continent', null, ['label'=>'Continent'])
            ;
    }
}
