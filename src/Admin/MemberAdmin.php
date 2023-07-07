<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Batch;
use App\Entity\Member;
use App\Entity\User;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\Type\ModelType;

final class MemberAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof Member
            ? $object->getFullName()
            : 'Member'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('fullName', null, ['label' => 'Full Name'])
            ->add('batch', null, [
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => Batch::class,
                    'choice_label' => 'code',
                ],
            ])
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
        $sortValues[DatagridInterface::SORT_BY] = 'fullName';
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('avatar', null, [
                'label' => 'Picture',
                'template' => '@SonataAdmin/CRUD/list_image.html.twig',
            ])
            ->add('fullName', null, ['label' => 'Full Name'])
            ->add('batch', null, ['label' => 'batch'])
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
        $form->tab('Member')
            ->with('Personnal Information', ['class' => 'col-md-6'])
            ->add('avatar', AdminType::class, [
                'delete' => false,
            ])
            ->add('fullName', null, ['label' => 'Full name'])
            ->add('batch', ModelType::class, [
                'class' => Batch::class,
                'property' => 'code',
            ])
            ->add('account', ModelType::class, [
                'class' => User::class,
                'property' => 'username',
            ])
            ->end()
            ->end();
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->tab('Member')
            ->with('Personnal Information', ['class' => 'col-md-6'])
            ->add('avatar', 'file', ['label' => 'Picture', 'template' => '@SonataAdmin/CRUD/base_show_image.html.twig'])
            ->add('fullName', null, ['label' => 'Full name'])
            ->add('batch')
            ->add('account')
            ->end()
            ->end();
    }

    protected function prePersist(object $member): void
    {
        $this->manageEmbeddedImageAdmins($member);
        $member->setCodeElect($member->genCodeElect());
    }

    protected function preUpdate(object $member): void
    {
        $this->manageEmbeddedImageAdmins($member);
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
