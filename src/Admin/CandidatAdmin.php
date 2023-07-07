<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Candidat;
use App\Entity\Member;
use App\Entity\Position;
use App\Entity\VotingSession;
use Iterator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\AdminBundle\Form\Type\ModelType;

final class CandidatAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof Candidat
            ? $object->__toString()
            : 'Candidat'; // shown in the breadcrumb on the create view
    }

    protected function prePersist(object $object): void
    {
        $object->setDateCreated(new \DateTimeImmutable());
        $object->setNumberVoter(0);
        $object->setDateUpdated(new \DateTimeImmutable());
    }

    protected function preUpdate(object $object): void
    {
        $object->setDateUpdated(new \DateTimeImmutable());
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('member', null, [
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => Member::class,
                    'choice_label' => 'fullName',
                ],
            ])
            ->add('position', null, [
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => Position::class,
                    'choice_label' => 'name',
                ],
            ])
            ->add('session', null, [
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => VotingSession::class,
                    'choice_label' => 'name',
                ],
            ])
            ->add('numberVoter', null, ['label' => 'Votes'])
            ->add('status', null, ['label' => 'activated']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('member.avatar', null, [
                'label' => 'Picture',
                'template' => '@SonataAdmin/CRUD/list_image.html.twig',
            ])
            ->add('member.fullName', null, ['label' => 'Full name'])
            ->add('position.name', null, ['label' => 'Position'])
            ->add('session.name', null, ['label' => 'Voting Session'])
            ->add('session.startingDate', null, ['label' => 'Voting starting date'])
            ->add('numberVoter', null, ['label' => 'Number of votes'])
            ->add('status')
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
            ->add('session', ModelType::class, [
                'class' => VotingSession::class,
                'property' => 'name',
            ])
            ->add('member', ModelType::class, [
                'class' => Member::class,
                'property' => 'fullName',
            ])
            ->add('position', ModelType::class, [
                'class' => Position::class,
                'property' => 'name',
            ])
            ->add('status');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('member.avatar', 'file', ['label' => 'Picture', 'template' => '@SonataAdmin/CRUD/base_show_image.html.twig'])
            ->add('member.fullName', null, ['label' => 'Full name'])
            ->add('position.name', null, ['label' => 'Position'])
            ->add('session.name', null, ['label' => 'Voting Session'])
            ->add('session.startingDate', null, ['label' => 'Voting starting date'])
            ->add('date_created', null, ['label' => 'Creating date'])
            ->add('dateUpdated', null, ['label' => 'Updating date'])
            ->add('numberVoter', null, ['label' => 'Number of votes'])
            ->add('status', null, ['label' => 'Activated']);
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        if ($this->isChild()) {
            return;
        }

        $collection->clearExcept(['list', 'show']);
    }

    protected function configureExportFields(): array
    {
        return ['session.name', 'session.startingDate', 'session.endingDate', 'id', 'member.avatar', 'member.fullName', 'member.batch', 'position.name', 'numberVoter', 'status', 'date_created', 'dateUpdated'];
    }
}
