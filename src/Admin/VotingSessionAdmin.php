<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\VotingSession;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class VotingSessionAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof VotingSession
            ? 'Session ' . $object->getName()
            : 'Voting Session'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('status')
            ->add('startingDate')
            ->add('endingDate')
            ->add('name');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('status')
            ->add('startingDate')
            ->add('endingDate')
            ->add('name')
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
            ->add('status')
            ->add('startingDate')
            ->add('endingDate')
            ->add('name');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('status')
            ->add('startingDate')
            ->add('endingDate')
            ->add('name');
    }

    protected function configureTabMenu(ItemInterface $menu, string $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        if ($this->isGranted('EDIT')) {
            $menu->addChild('Update', $admin->generateMenuUrl('edit', ['id' => $id]));
        }

        if ($this->isGranted('LIST')) {
            $menu->addChild('Candidats', $admin->generateMenuUrl('admin.candidat.list', ['id' => $id]));
        }
    }
}
