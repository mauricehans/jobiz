<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\JobApplication;
use App\Form\JobApplicationType;
use App\Repository\JobCategoryRepository;
use App\Repository\JobRepository;
use App\Repository\JobTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted as SensioIsGranted;

#[Route('/jobs')]
class JobController extends AbstractController
{
    public function __construct(
        private JobRepository $jobRepository,
        private JobCategoryRepository $categoryRepository,
        private JobTypeRepository $typeRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'app_job_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $category = $request->query->get('category');
        $country = $request->query->get('country');
        $page = $request->query->getInt('page', 1);

        $jobs = $this->jobRepository->findByFilters($category, $country, $page);
        $categories = $this->categoryRepository->findAll();
        $countries = $this->jobRepository->findAllCountries();

        return $this->render('job/index.html.twig', [
            'jobs' => $jobs,
            'categories' => $categories,
            'countries' => $countries,
            'current_category' => $category,
            'current_country' => $country,
        ]);
    }

    #[Route('/{id}', name: 'app_job_show', methods: ['GET'])]
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    #[Route('/{id}/apply', name: 'app_job_apply', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function apply(Request $request, Job $job): Response
    {
        $application = new JobApplication();
        $application->setJob($job);
        $application->setUser($this->getUser());

        $form = $this->createForm(JobApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($application);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your application has been submitted successfully!');
            return $this->redirectToRoute('app_job_show', ['id' => $job->getId()]);
        }

        return $this->render('job/apply.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_job_new', methods: ['GET', 'POST'])]
    #[SensioIsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $job = new Job();
        $form = $this->createForm(\App\Form\JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($job);
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre créée avec succès !');
            return $this->redirectToRoute('app_job_index');
        }

        return $this->render('job/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_job_edit', methods: ['GET', 'POST'])]
    #[SensioIsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Job $job): Response
    {
        $form = $this->createForm(\App\Form\JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre modifiée avec succès !');
            return $this->redirectToRoute('app_job_index');
        }

        return $this->render('job/edit.html.twig', [
            'form' => $form,
            'job' => $job,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_job_delete', methods: ['POST'])]
    #[SensioIsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Job $job): Response
    {
        if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($job);
            $this->entityManager->flush();
            $this->addFlash('success', 'Offre supprimée avec succès !');
        }
        return $this->redirectToRoute('app_job_index');
    }
} 