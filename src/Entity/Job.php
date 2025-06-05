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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function isRemoteAllowed(): ?bool
    {
        return $this->remoteAllowed;
    }

    public function setRemoteAllowed(bool $remoteAllowed): static
    {
        $this->remoteAllowed = $remoteAllowed;
        return $this;
    }

    public function getSalaryMin(): ?float
    {
        return $this->salaryMin;
    }

    public function setSalaryMin(float $salaryMin): static
    {
        $this->salaryMin = $salaryMin;
        return $this;
    }

    public function getSalaryMax(): ?float
    {
        return $this->salaryMax;
    }

    public function setSalaryMax(float $salaryMax): static
    {
        $this->salaryMax = $salaryMax;
        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;
        return $this;
    }

    public function getJobType(): ?JobType
    {
        return $this->jobType;
    }

    public function setJobType(?JobType $jobType): static
    {
        $this->jobType = $jobType;
        return $this;
    }

    /**
     * @return Collection<int, JobCategory>
     */
    public function getJobCategories(): Collection
    {
        return $this->jobCategories;
    }

    public function addJobCategory(JobCategory $jobCategory): static
    {
        if (!$this->jobCategories->contains($jobCategory)) {
            $this->jobCategories->add($jobCategory);
        }
        return $this;
    }

    public function removeJobCategory(JobCategory $jobCategory): static
    {
        $this->jobCategories->removeElement($jobCategory);
        return $this;
    }

    /**
     * @return Collection<int, JobApplication>
     */
    public function getJobApplications(): Collection
    {
        return $this->jobApplications;
    }

    public function addJobApplication(JobApplication $jobApplication): static
    {
        if (!$this->jobApplications->contains($jobApplication)) {
            $this->jobApplications->add($jobApplication);
            $jobApplication->setJob($this);
        }
        return $this;
    }

    public function removeJobApplication(JobApplication $jobApplication): static
    {
        if ($this->jobApplications->removeElement($jobApplication)) {
            if ($jobApplication->getJob() === $this) {
                $jobApplication->setJob(null);
            }
        }
        return $this;
    }
}