<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Provider", mappedBy="services")
    */
    private $providers;

    public function __construct()
    {
        $this->providers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection
     */
    public function getProviders(): Collection
    {
        return $this->providers;
    }

    /**
     * @param Collection $providers
     */
    public function setProviders(array $providers): void
    {
        $this->providers = new ArrayCollection($providers);
    }

    public function addProvider(Provider $provider): void
    {
        if (!$this->providers->contains($provider)) {
            $this->providers->add($provider);
        }
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
