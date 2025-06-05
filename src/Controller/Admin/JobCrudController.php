<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JobCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Job::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Job')
            ->setEntityLabelInPlural('Jobs')
            ->setSearchFields(['title', 'description', 'company.name', 'city', 'country'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield TextareaField::new('description');
        yield TextField::new('country');
        yield TextField::new('city');
        yield BooleanField::new('remoteAllowed');
        yield TextField::new('salaryRange');
        yield AssociationField::new('company');
        yield AssociationField::new('categories');
        yield AssociationField::new('type');
        yield DateTimeField::new('createdAt')
            ->hideOnForm();
        yield AssociationField::new('applications')
            ->hideOnForm();
    }
} 