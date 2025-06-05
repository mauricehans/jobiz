<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Job::class)]
    private Collection $jobs;

    public function getName(): ?string
{
    return $this->name;
}

public function setName(string $name): static
{
    $this->name = $name;
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

public function getAddress(): ?string
{
    return $this->address;
}

public function setAddress(string $address): static
{
    $this->address = $address;
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

public function getCountry(): ?string
{
    return $this->country;
}

public function setCountry(string $country): static
{
    $this->country = $country;
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
        $job->setCompany($this);
    }
    return $this;
}

public function removeJob(Job $job): static
{
    if ($this->jobs->removeElement($job)) {
        if ($job->getCompany() === $this) {
            $job->setCompany(null);
        }
    }
    return $this;
}
}
