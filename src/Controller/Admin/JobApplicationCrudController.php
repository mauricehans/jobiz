<?php

namespace App\Controller\Admin;

use App\Entity\JobApplication;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class JobApplicationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobApplication::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Job Application')
            ->setEntityLabelInPlural('Job Applications')
            ->setSearchFields(['user.email', 'job.title', 'coverLetter'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user');
        yield AssociationField::new('job');
        yield TextareaField::new('coverLetter');
        yield DateTimeField::new('createdAt')
            ->hideOnForm();
    }
} 