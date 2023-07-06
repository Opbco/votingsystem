<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Departement;
use App\Entity\SubDivision;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Show\ShowMapper;

final class SubDivisionAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof SubDivision
            ? $object->getName()
            : 'SubDivision'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('department', null, ['label'=>'Division'])
            ->add('department.state', null, ['label'=>'State'])
            ->add('department.state.country', null, ['label'=>'Country'])
            ->add('department.state.country.continent', null, ['label'=>'Continent'])
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name')
            ->add('department', null, ['label'=>'Division'])
            ->add('department.state', null, ['label'=>'State'])
            ->add('department.state.country', null, ['label'=>'Country'])
            ->add('department.state.country.continent', null, ['label'=>'Continent'])
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
            ->add('department', ModelListType::class, [
                'class' => Departement::class,
                'label' => "Division",
                'btn_delete' => false,
            ]);
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('department', null, ['label'=>'Division'])
            ->add('department.state', null, ['label'=>'State'])
            ->add('department.state.country', null, ['label'=>'Country'])
            ->add('department.state.country.continent', null, ['label'=>'Continent'])
            ;
    }
}
