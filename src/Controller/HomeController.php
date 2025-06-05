<?php

namespace App\Controller;

use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(JobRepository $jobRepository): Response
    {
        $latestJobs = $jobRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render('home/index.html.twig', [
            'latest_jobs' => $latestJobs,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }
}