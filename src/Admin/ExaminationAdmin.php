<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Examination;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeFilter;
use Sonata\Form\Type\DatePickerType;

final class ExaminationAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof Examination
            ? $object->getCode()
            : 'Examination'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('code')
            ->add('schoolYear', null, ['label' => 'School year'])
            ->add('openDate', DateTimeFilter::class, ['label' => 'Opening Date'])
            ->add('closingDate', DateTimeFilter::class, ['label' => 'Closing Date']);
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        // display the first page (default = 1)
        $sortValues[DatagridInterface::PAGE] = 1;

        // reverse order (default = 'ASC')
        $sortValues[DatagridInterface::SORT_ORDER] = 'DESC';

        // name of the ordered field (default = the model's id field, if any)
        $sortValues[DatagridInterface::SORT_BY] = 'schoolYear';
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('code')
            ->add('schoolYear', null, ['label' => 'School year'])
            ->add('openDate', null, ['label' => 'Opening Date'])
            ->add('closingDate', null, ['label' => 'Closing Date'])
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
            ->add('code')
            ->add('schoolYear', null, ['label' => 'School year'])
            ->add('openDate', DatePickerType::class, ['label' => 'Opening Date'])
            ->add('closingDate', DatePickerType::class, ['label' => 'Closing Date']);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('code')
            ->add('schoolYear', null, ['label' => 'School year'])
            ->add('openDate', null, ['label' => 'Opening Date'])
            ->add('closingDate', null, ['label' => 'Closing Date']);
    }
}
