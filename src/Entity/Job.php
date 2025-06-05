<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column]
    private ?bool $remoteAllowed = null;

    #[ORM\Column(type: 'float')]
    private ?float $salaryMin = null;

    #[ORM\Column(type: 'float')]
    private ?float $salaryMax = null;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JobType $jobType = null;

    #[ORM\ManyToMany(targetEntity: JobCategory::class, inversedBy: 'jobs')]
    private Collection $jobCategories;

    #[ORM\OneToMany(mappedBy: 'job', targetEntity: JobApplication::class)]
    private Collection $jobApplications;

    public function __construct()
    {
        $this->jobCategories = new ArrayCollection();
        $this->jobApplications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
