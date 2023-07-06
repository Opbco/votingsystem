<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Candidat;
use App\Entity\Diploma;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;

final class DiplomaAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof Diploma
            ? $object->getName()
            : 'Diploma'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('speciality', null, ['label' => 'Speciality'])
            ->add('year', null, ['label' => 'Year of obtention'])
            ->add('score', null, ['label' => 'Average'])
            ->add('school', null, ['label' => 'School'])
            ->add('candidat', ModelFilter::class, [
                'field_type' => ModelAutocompleteType::class,
                'label' => 'Candidat',
                'field_options' => ['class' => Candidat::class, 'property' => 'full_name'],
            ]);
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        // display the first page (default = 1)
        $sortValues[DatagridInterface::PAGE] = 1;

        // reverse order (default = 'ASC')
        $sortValues[DatagridInterface::SORT_ORDER] = 'DESC';

        // name of the ordered field (default = the model's id field, if any)
        $sortValues[DatagridInterface::SORT_BY] = 'year';
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name')
            ->add('candidat', null, array('label' => 'Candidat'))
            ->add('speciality', null, ['label' => 'Speciality'])
            ->add('year', null, ['label' => 'Year of obtention'])
            ->add('score', null, ['label' => 'Average'])
            ->add('school', null, ['label' => 'School'])
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
            ->add('speciality', null, ['label' => 'Speciality'])
            ->add('year', null, ['label' => 'Year of obtention'])
            ->add('score', null, ['label' => 'Average'])
            ->add('school', null, ['label' => 'School'])
            ->add('candidat', ModelListType::class, [
                'class' => Candidat::class,
                'label' => "Concerned",
                'btn_delete' => false,
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('speciality', null, ['label' => 'Speciality'])
            ->add('year', null, ['label' => 'Year of obtention'])
            ->add('score', null, ['label' => 'Average'])
            ->add('school', null, ['label' => 'School'])
            ->add('candidat', null, array('label' => 'Candidat'));
    }
}
