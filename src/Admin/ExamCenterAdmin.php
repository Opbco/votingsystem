<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Admin;

use App\Entity\Departement;
use App\Entity\ExamCenter;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ExamCenterAdmin extends AbstractAdmin
{

    public function toString(object $object): string
    {
        return $object instanceof ExamCenter
            ? $object->getName()
            : 'Examination Center'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('name', TextType::class, array('label' => 'Nom'))
            ->add('town', TextType::class, array('label' => 'Town'))
            ->add('division', ModelListType::class, [
                'class' => Departement::class,
                'label' => "Division",
                'btn_delete' => false,
            ]);
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        // display the first page (default = 1)
        $sortValues[DatagridInterface::PAGE] = 1;

        // reverse order (default = 'ASC')
        $sortValues[DatagridInterface::SORT_ORDER] = 'ASC';

        // name of the ordered field (default = the model's id field, if any)
        $sortValues[DatagridInterface::SORT_BY] = 'town';
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('name', null, array('label' => 'Name'))
            ->add('town', null, array('label' => 'Town'))
            ->add('division', null, ['label' => 'Division'])
            ->add('division.state', null, ['label' => 'Region']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('name')
            ->add('town', null, ['label' => 'Town'])
            ->add('division', null, ['label' => 'Division'])
            ->add('division.state', null, ['label' => 'Region'])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }
}
