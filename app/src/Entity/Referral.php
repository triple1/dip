<?php

namespace App\Entity;

use App\Repository\ReferralRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReferralRepository::class)
 */
class Referral
{
    public const STATUS_NEW = 'STATUS_NEW';
    public const STATUS_DONE = 'STATUS_DONE';
    public const STATUS_REJECT = 'STATUS_REJECT';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
    */
    private $status;

    /**
     * @var Participant
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Participant")
     * @ORM\JoinColumn(name="id_participant", referencedColumnName="id")
    */
    private $participant;

    /**
     * @var Provider
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider")
     * @ORM\JoinColumn(name="id_provider", referencedColumnName="id")
    */
    private $provider;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Service")
     * @ORM\JoinTable(name="referrals_services",
     *     joinColumns={@ORM\JoinColumn(name="id_referral", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_service", referencedColumnName="id")}
     *     )
    */
    private $services;

    public function __construct()
    {
        $this->dateCreatedAt = new \DateTime();
        $this->status = self::STATUS_NEW;
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param Collection $services
     */
    public function setServices(array $services): void
    {
        $this->services = new ArrayCollection($services);
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Participant
     */
    public function getParticipant(): Participant
    {
        return $this->participant;
    }

    /**
     * @param Participant $participant
     */
    public function setParticipant(Participant $participant): void
    {
        $this->participant = $participant;
    }

    /**
     * @return Provider
     */
    public function getProvider(): Provider
    {
        return $this->provider;
    }

    /**
     * @param Provider $provider
     */
    public function setProvider(Provider $provider): void
    {
        $this->provider = $provider;
    }

    public function getDateCreatedAt(): ?\DateTimeInterface
    {
        return $this->dateCreatedAt;
    }

    public function setDateCreatedAt(\DateTimeInterface $dateCreatedAt): self
    {
        $this->dateCreatedAt = $dateCreatedAt;

        return $this;
    }
}
