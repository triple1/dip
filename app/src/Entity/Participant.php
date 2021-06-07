<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
    */
    private $dateCreatedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
    */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     */
    private $lastName;

    /**
     * @var datetime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dob;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $phone;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Provider", mappedBy="participants")
    */
    private $providers;

    /**
     * @var Provider
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider")
     * @ORM\JoinColumn(name="id_provider", referencedColumnName="id")
    */
    private $ownerProvider;

    public function __construct()
    {
        $this->providers = new ArrayCollection();
        $this->dateCreatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Provider|null
     */
    public function getOwnerProvider(): ?Provider
    {
        return $this->ownerProvider;
    }

    /**
     * @param Provider $ownerProvider
     */
    public function setOwnerProvider(Provider $ownerProvider): void
    {
        $this->ownerProvider = $ownerProvider;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreatedAt(): \DateTime
    {
        return $this->dateCreatedAt;
    }

    /**
     * @return Collection
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * @param Collection $providers
     */
    public function setProviders(array $providers): void
    {
        /** @var Provider $provider */
        foreach ($this->providers as $provider) {
            $provider->getParticipants()->removeElement($this);
        }
        $this->providers = new ArrayCollection($providers);
    }

    public function addProvider(Provider $provider): void
    {
        if (!$this->providers->contains($provider)) {
            $this->providers->add($provider);
            $provider->addParticipant($this);
        }
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return \DateTime|null
     */
    public function getDob(): ?\DateTime
    {
        return $this->dob;
    }

    /**
     * @param \DateTime|null $dob
     */
    public function setDob(?\DateTime $dob): void
    {
        $this->dob = $dob;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
}
