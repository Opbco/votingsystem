<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Candidat;
use App\Entity\Speciality;
use App\Entity\CandidatConcours;
use App\Entity\Departement;
use App\Entity\Examination;
use App\Entity\ExamCenter;
use App\Entity\State;
use App\Form\CandidatureStatus;
use App\Form\DataTransformer\CandidatureStatusDataTransformer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType as FilterChoiceType;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

final class CandidatConcoursAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof CandidatConcours
            ? $object . ' '
            : 'Candidat et concours'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('dateCreated', null, ['label' => "Registering date"])
            ->add('nbAttempt', null, ['label' => "Number of tries"])
            ->add('status', ChoiceFilter::class, [
                'global_search' => true,
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => CandidatureStatus::choices(),
                ], array('label' => 'Status')
            ])
            ->add('level', ChoiceFilter::class, [
                'global_search' => true,
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => [
                        'First year' => "first year",
                        'Second year' => 'second year',
                        'Third year' => 'third year',
                    ]
                ], array('label' => 'Level')
            ])
            ->add('language', ChoiceFilter::class, [
                'global_search' => true,
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => [
                        'French' => "french",
                        'English' => 'english',
                    ]
                ], array('label' => 'Language')
            ])
            ->add('concours', ModelFilter::class, [
                'field_type' => ModelAutocompleteType::class,
                'label' => 'Examination',
                'field_options' => ['class' => Examination::class, 'property' => 'code'],
            ])
            ->add('candidat', ModelFilter::class, [
                'field_type' => ModelAutocompleteType::class,
                'label' => 'Candidat',
                'field_options' => ['class' => Candidat::class, 'property' => 'full_name'],
            ])
            ->add('speciality', ModelFilter::class, [
                'field_type' => ModelAutocompleteType::class,
                'label' => 'Speciality',
                'field_options' => ['class' => Speciality::class, 'property' => 'name'],
            ])
            ->add('examCenter', ModelFilter::class, [
                'field_type' => ModelAutocompleteType::class,
                'label' => 'Examination center',
                'field_options' => ['class' => ExamCenter::class, 'property' => 'name'],
            ])
            ->add('examCenter.division', null, [
                'field_type' => EntityType::class,
                'label' => 'Division',
                'field_options' => [
                    'class' => Departement::class,
                    'choice_label' => 'name',
                ],
            ])
            ->add('examCenter.division.state', null, [
                'field_type' => EntityType::class,
                'label' => 'Region',
                'field_options' => [
                    'class' => State::class,
                    'choice_label' => 'name',
                ],
            ]);
    }

    protected function preUpdate(object $object): void
    {
        $object->setDateUpdated(new \DateTimeImmutable());
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('dateCreated', null, ['label' => 'Date'])
            ->add('level', null, ['label' => 'Level'])
            ->add('language', null, ['label' => 'Language'])
            ->add('concours', null, ['label' => 'Examination'])
            ->add('candidat', null, ['label' => 'Candidat'])
            ->add('status', 'choice', [
                'editable' => true,
                'choices' => CandidatureStatus::choices(),
                'data_transformer' => new CandidatureStatusDataTransformer(),
            ])
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
            ->add('dateCreated', DateTimePickerType::class, ['label' => "Date"])
            ->add('language', ChoiceType::class, [
                'choices' => [
                    'French' => "french",
                    'English' => 'english',
                ],
                'label' => 'Choose the language',
                'required' => true
            ])
            ->add('level', ChoiceType::class, [
                'choices' => [
                    'First year' => "first year",
                    'Second year' => 'second year',
                    'Third year' => 'third year',
                ],
                'label' => 'Choose the level',
                'required' => true
            ])
            ->add('nbAttempt', IntegerType::class, ['label' => "Number of tries"])
            ->add('concours', ModelType::class, ['label' => 'Examination', 'required' => true])
            ->add('candidat', ModelType::class, ['label' => 'Candidat', 'required' => true])
            ->add('speciality', ModelType::class, ['label' => 'Speciality', 'required' => true])
            ->add('examCenter', ModelType::class, ['label' => 'Examination center', 'required' => true])
            ->add('status', ChoiceType::class, [
                'placeholder' => 'Status',
                'choices' => CandidatureStatus::choices(),
                'required' => true,
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('dateCreated', null, ['label' => "Date of registering"])
            ->add('dateUpdated', null, ['label' => "Date of last update"])
            ->add('language', ChoiceType::class, [
                'choices' => [
                    'French' => "french",
                    'English' => 'english',
                ],
                'label' => 'Choose the language',
                'required' => true
            ])
            ->add('level', ChoiceType::class, [
                'choices' => [
                    'First year' => "first year",
                    'Second year' => 'second year',
                    'Third year' => 'third year',
                ],
                'label' => 'Choose the level',
                'required' => true
            ])
            ->add('nbAttempt', null, ['label' => "Number of tries"])
            ->add('concours', null, ['label' => 'Examination', 'required' => true])
            ->add('candidat', null, ['label' => 'Candidat', 'required' => true])
            ->add('speciality', null, ['label' => 'Speciality', 'required' => true])
            ->add('examCenter', null, ['label' => 'Examination center', 'required' => true])
            ->add('status');
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {

        if ($this->isChild()) {
            return;
        }

        // This is the route configuration as a parent
        $collection->clearExcept(['list', 'show']);
    }
}
