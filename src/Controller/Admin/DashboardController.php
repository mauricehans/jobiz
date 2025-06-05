<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\Job;
use App\Entity\JobApplication;
use App\Entity\JobCategory;
use App\Entity\JobType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $jobsCount = $this->entityManager->getRepository(Job::class)->count([]);
        $activeJobsCount = $this->entityManager->getRepository(Job::class)->count(['active' => true]);
        
        $applicationsCount = $this->entityManager->getRepository(JobApplication::class)->count([]);
        $newApplicationsCount = $this->entityManager->getRepository(JobApplication::class)
            ->count(['createdAt' => new \DateTime('-7 days')]);
        
        $companiesCount = $this->entityManager->getRepository(Company::class)->count([]);
        $activeCompaniesCount = $this->entityManager->getRepository(Company::class)
            ->count(['active' => true]);

        $recentApplications = $this->entityManager->getRepository(JobApplication::class)
            ->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'jobs_count' => $jobsCount,
            'active_jobs_count' => $activeJobsCount,
            'applications_count' => $applicationsCount,
            'new_applications_count' => $newApplicationsCount,
            'companies_count' => $companiesCount,
            'active_companies_count' => $activeCompaniesCount,
            'recent_applications' => $recentApplications,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Jobiz Admin')
            ->setFaviconPath('favicon.svg');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Jobs');
        yield MenuItem::linkToCrud('Jobs', 'fa fa-briefcase', Job::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-tags', JobCategory::class);
        yield MenuItem::linkToCrud('Types', 'fa fa-list', JobType::class);
        yield MenuItem::linkToCrud('Applications', 'fa fa-file-alt', JobApplication::class);
        yield MenuItem::section('Companies');
        yield MenuItem::linkToCrud('Companies', 'fa fa-building', Company::class);
    }
}
