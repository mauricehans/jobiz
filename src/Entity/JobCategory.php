<?php

namespace App\Entity;

use App\Repository\JobCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobCategoryRepository::class)]
class JobCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Job::class, mappedBy: 'jobCategories')]
    private Collection $jobs;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, Job>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): static
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
            $job->addJobCategory($this);
        }
        return $this;
    }

    public function removeJob(Job $job): static
    {
        if ($this->jobs->removeElement($job)) {
            $job->removeJobCategory($this);
        }
        return $this;
    }
}
