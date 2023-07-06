<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Candidat;
use App\Entity\Departement;
use App\Form\Type\GenderType;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Filter\Model\FilterData;
use Sonata\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\DoctrineORMAdminBundle\Filter\CallbackFilter;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface as DatagridProxyQueryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CandidatAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof Candidat
            ? $object->getName()
            : 'Candidat'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('full_name', CallbackFilter::class, [
                'callback' => [$this, 'getFullNameFilter'],
                'field_type' => TextType::class,
                'label' => 'Full Name',
            ])
            ->add('dob', null, ['label' => 'Date of birth'])
            ->add('pob', null, ['label' => 'Place of birth'])
            ->add('gender', ChoiceFilter::class, [
                'global_search' => true,
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => [
                        'Masculin' => 'Male',
                        'Feminin' => 'Female',
                        'Autre' => 'Other',
                    ]
                ], array('label' => 'Gender')
            ])
            ->add('nationality', null, ['label' => 'Nationality'])
            ->add('divisionOrigin', null, [
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => Departement::class,
                    'choice_label' => 'name',
                ],
            ])
            ->add('email')
            ->add('bp', null, ['label' => 'Post Office (P.O)'])
            ->add('phone')
            ->add('whatsapp')
            ->add('fatherName', null, ['label' => 'Father Name'])
            ->add('motherName', null, ['label' => 'Mother Name']);
    }

    public function getFullNameFilter(ProxyQueryInterface $query, string $alias, string $field, FilterData $data): bool
    {
        if (!$data->hasValue()) {
            return false;
        }

        $query->andWhere($query->expr()->orX(
            $query->expr()->like($alias . '.lastName', $query->expr()->literal('%' . $data->getValue() . '%')),
            $query->expr()->like($alias . '.firstName', $query->expr()->literal('%' . $data->getValue() . '%'))
        ));

        return true;
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        // display the first page (default = 1)
        $sortValues[DatagridInterface::PAGE] = 1;

        // reverse order (default = 'ASC')
        $sortValues[DatagridInterface::SORT_ORDER] = 'ASC';

        // name of the ordered field (default = the model's id field, if any)
        $sortValues[DatagridInterface::SORT_BY] = 'firstName';
    }


    protected function configureQuery(DatagridProxyQueryInterface $query): DatagridProxyQueryInterface
    {
        $rootAlias = current($query->getRootAliases());

        $query->addOrderBy($rootAlias . '.lastName', 'ASC');

        return $query;
    }


    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name')
            ->add('dob', null, ['label' => 'Date of birth'])
            ->add('gender', FieldDescriptionInterface::TYPE_CHOICE, [
                'choices' => [
                    'Masculin' => 'Male',
                    'Feminin' => 'Female',
                    'Autre' => 'Other',
                ],
            ])
            ->add('nationality', null, ['label' => 'Nationality'])
            ->add('divisionOrigin', null, ['label' => 'Division Of Origin'])
            ->add('divisionOrigin.state', null, ['label' => 'Region Of Origin'])
            ->add('phone')
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
        $form->tab('Candidat')
            ->with('Personnal Information', ['class' => 'col-md-6'])
            ->add('avatar', AdminType::class, [
                'delete' => false,
            ])
            ->add('firstName', null, ['label' => 'First Name'])
            ->add('lastName', null, ['label' => 'Last Name'])
            ->add('dob', DatePickerType::class, ['label' => 'Date of birth'])
            ->add('pob', null, ['label' => 'Place of birth'])
            ->add('gender', GenderType::class, ['label' => 'Gender'])
            ->add('divisionOrigin', ModelType::class, [
                'class' => Departement::class,
                'property' => 'name',
            ])
            ->add('nationality', null, ['label' => 'Nationality'])
            ->add('phone')
            ->add('whatsapp', null, ['label' => 'WhatsApp number'])
            ->add('email')
            ->add('bp', null, ['label' => 'Post Office (P.O)'])
            ->end()
            ->end()
            ->tab('Parents')
            ->with('Parents Information')
            ->add('fatherName', null, ['label' => 'Father Name'])
            ->add('motherName', null, ['label' => 'Mother Name'])
            ->add('parentsPhones', null, ['label' => 'Parent Contact(s)'])
            ->end()
            ->end()
            ->tab('Documentation')
            ->with('Diplomes', ['class' => 'col-md-12'])
            ->add('diplomas', CollectionType::class, [
                'type_options' => [
                    // Prevents the "Delete" option from being displayed
                    'delete' => true,
                    'delete_options' => [
                        // You may otherwise choose to put the field but hide it
                        'type'         => CheckboxType::class,
                        // In that case, you need to fill in the options as well
                        'type_options' => [
                            'mapped'   => false,
                            'required' => false,
                        ]
                    ]
                ]
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ])
            ->end()
            ->end();
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->tab('Candidat')
            ->with('Personnal Information', ['class' => 'col-md-6'])
            ->add('avatar', 'file', ['label' => 'Picture', 'template' => '@SonataAdmin/CRUD/base_show_file.html.twig'])
            ->add('firstName', null, ['label' => 'First Name'])
            ->add('lastName', null, ['label' => 'Last Name'])
            ->add('dob', null, ['label' => 'Date of birth'])
            ->add('pob', null, ['label' => 'Place of birth'])
            ->add('gender', GenderType::class, ['label' => 'Gender'])
            ->add('divisionOrigin', null, ['label' => 'Division of Origin'])
            ->add('nationality', null, ['label' => 'Nationality'])
            ->add('phone')
            ->add('whatsapp', null, ['label' => 'WhatsApp number'])
            ->add('email')
            ->add('bp', null, ['label' => 'Post Office (P.O)'])
            ->end()
            ->end()
            ->tab('Parents')
            ->with('Parents Information')
            ->add('fatherName', null, ['label' => 'Father Name'])
            ->add('motherName', null, ['label' => 'Mother Name'])
            ->add('parentsPhones', null, ['label' => 'Parent Contact(s)'])
            ->end()
            ->end();
    }

    protected function prePersist(object $Candidat): void
    {
        $this->manageEmbeddedImageAdmins($Candidat);
    }

    protected function preUpdate(object $Candidat): void
    {
        $this->manageEmbeddedImageAdmins($Candidat);
    }

    private function manageEmbeddedImageAdmins(object $page): void
    {
        // Cycle through each field
        foreach ($this->getFormFieldDescriptions() as $fieldName => $fieldDescription) {
            // detect embedded Admins that manage Images
            if (
                $fieldDescription->getType() === AdminType::class &&
                ($associationMapping = $fieldDescription->getAssociationMapping()) &&
                $associationMapping['targetEntity'] === 'App\Entity\Document'
            ) {
                $getter = 'get' . $fieldName;
                $setter = 'set' . $fieldName;

                /** @var Image $image */
                $image = $page->$getter();

                if ($image) {
                    if ($image->getFile()) {
                        // update the Image to trigger file management
                        $image->refreshUpdated();
                    } elseif (!$image->getFile() && !$image->getFileName()) {
                        // prevent Sf/Sonata trying to create and persist an empty Image
                        $page->$setter(null);
                    }
                }
            }
        }
    }

    protected function configureTabMenu(ItemInterface $menu, string $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        $menu->addChild('Show', $admin->generateMenuUrl('show', ['id' => $id]));

        if ($this->isGranted('EDIT')) {
            $menu->addChild('Edit', $admin->generateMenuUrl('edit', ['id' => $id]));
        }

        if ($this->isGranted('LIST')) {
            $menu->addChild('Concours', $admin->generateMenuUrl('admin.candidatconcours.list', ['id' => $id]));
        }
    }
}
