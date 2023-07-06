<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Membre;
use App\Entity\User;
use App\Form\Type\GenderType;
use App\Form\Type\MaritalType;
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
use Oh\GoogleMapFormTypeBundle\Form\Type\GoogleMapType;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class MembreAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof Membre
            ? $object->getName()
            : 'Membre'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('title', null, ['label' => 'Title'])
            ->add('name', null, ['label' => 'Full Name'])
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
            ->add('maritalStatus', ChoiceFilter::class, [
                'global_search' => true,
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => [
                        'Marie(e)' => 'Maried',
                        'Celibataire' => 'Single',
                        'Veuf(ve)' => 'Widow',
                        'Divorce' => 'Divorced'
                    ]
                ], array('label' => 'Gender')
            ])
            ->add('ethnie', null, ['label' => 'Ethnic'])
            ->add('profession')
            ->add('handicap')
            ->add('conjointName', null, ['label' => 'Spouse Name'])
            ->add('conjointFonction', null, ['label' => 'Spouse profession'])
            ->add('conjointContact', null, ['label' => 'Contact'])
            ->add('nbreEnfant', null, ['label' => 'Number of children'])
            ->add('phone')
            ->add('whatsapp')
            ->add('motherName', null, ['label' => 'Mother Name'])
            ->add('fatherName', null, ['label' => 'Father Name'])
            ->add('account', null, [
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => User::class,
                    'choice_label' => 'username',
                ],
            ]);
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        // display the first page (default = 1)
        $sortValues[DatagridInterface::PAGE] = 1;

        // reverse order (default = 'ASC')
        $sortValues[DatagridInterface::SORT_ORDER] = 'ASC';

        // name of the ordered field (default = the model's id field, if any)
        $sortValues[DatagridInterface::SORT_BY] = 'name';
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
            ->add('maritalStatus', FieldDescriptionInterface::TYPE_CHOICE, [
                'choices' => [
                    'Marie(e)' => 'Maried',
                    'Celibataire' => 'Single',
                    'Veuf(ve)' => 'Widow',
                    'Divorce' => 'Divorced'
                ],
            ])
            ->add('ethnie', null, ['label' => 'Ethnic'])
            ->add('profession', null, ['label' => 'Profession'])
            ->add('phone')
            ->add('isFeatured', null, ['label' => 'Is Featured?'])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'print' => [
                        'template' => '@SonataAdmin/CRUD/list__action_print.html.twig',
                    ],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->tab('Membre')
            ->with('Personnal Information', ['class' => 'col-md-6'])
            ->add('avatar', AdminType::class, [
                'delete' => false,
            ])
            ->add('title', null, ['label' => 'Title'])
            ->add('name', null, ['label' => 'Full name'])
            ->add('dob', DatePickerType::class, ['label' => 'Date of birth'])
            ->add('pob', null, ['label' => 'Place of birth'])
            ->add('gender', GenderType::class, ['label' => 'Gender'])
            ->add('maritalStatus', MaritalType::class, ['label' => 'Marital status'])
            ->add('ethnie', null, ['label' => 'Ethnic'])
            ->add('profession', null, ['label' => 'Profession'])
            ->add('handicap', TextareaType::class, ['label' => 'Handicaps'])
            ->add('phone')
            ->add('whatsapp', null, ['label' => 'WhatsApp number'])
            ->add('motherName', null, ['label' => 'Mother name'])
            ->add('fatherName', null, ['label' => 'Father name'])
            ->end()
            ->with('Account & Localisation', ['class' => 'col-md-6'])
            ->add('latlng', GoogleMapType::class, ['label' => 'Latitude & Longitude'])
            ->add('account', ModelType::class, [
                'class' => User::class,
                'property' => 'username',
            ])
            ->add('isFeatured', null, ['label' => 'Is Featured?'])
            ->end()
            ->with('Description', ['class' => 'col-md-6'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->end()
            ->end()
            ->tab('Conjoint')
            ->with('Personnal Information')
            ->add('conjointName', null, ['label' => 'Full Name'])
            ->add('conjointFonction', null, ['label' => 'Profession'])
            ->add('conjointAdress', null, ['label' => 'Address'])
            ->add('conjointContact', null, ['label' => 'Contact'])
            ->add('nbreEnfant', null, ['label' => 'Number of children'])
            ->end()
            ->end();
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->tab('Membre')
            ->with('Personnal Information', ['class' => 'col-md-6'])
            ->add('avatar', 'file', ['label' => 'Picture', 'template' => '@SonataAdmin/CRUD/base_show_file.html.twig'])
            ->add('title', null, ['label' => 'Title'])
            ->add('name', null, ['label' => 'Full name'])
            ->add('dob', null, ['label' => 'Date of birth'])
            ->add('pob', null, ['label' => 'Place of birth'])
            ->add('gender', GenderType::class, ['label' => 'Gender'])
            ->add('maritalStatus', MaritalType::class, ['label' => 'Marital status'])
            ->add('ethnie', null, ['label' => 'Ethnic'])
            ->add('profession', null, ['label' => 'Profession'])
            ->add('handicap', null, ['label' => 'Handicaps'])
            ->add('phone')
            ->add('whatsapp', null, ['label' => 'WhatsApp number'])
            ->add('motherName', null, ['label' => 'Mother name'])
            ->add('fatherName', null, ['label' => 'Father name'])
            ->end()
            ->with('Compte', ['class' => 'col-md-6'])
            ->add('address', null, ['label' => 'Adresse'])
            ->add('latitude', null, ['label' => 'Latitude'])
            ->add('longitude', null, ['label' => 'Longitude'])
            ->add('account')
            ->end()
            ->with('Description', ['class' => 'col-md-6'])
            ->add('description', null, ['label' => 'Description'])
            ->end()
            ->with('Consecration', ['class' => 'col-md-6'])
            ->add('dateConsecration', null, ['label' => 'Date'])
            ->add('paroisseConsecration', null, ['label' => 'Paroisse'])
            ->end()
            ->end()
            ->tab('Conjoint')
            ->with('Personnal Information')
            ->add('conjointName', null, ['label' => 'Full Name'])
            ->add('conjointFonction', null, ['label' => 'Profession'])
            ->add('conjointAdress', null, ['label' => 'Address'])
            ->add('conjointContact', null, ['label' => 'Contact'])
            ->add('nbreEnfant', null, ['label' => 'Number of children'])
            ->end()
            ->end();
    }

    protected function prePersist(object $membre): void
    {
        $this->manageEmbeddedImageAdmins($membre);
    }

    protected function preUpdate(object $membre): void
    {
        $this->manageEmbeddedImageAdmins($membre);
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
    }
}
