<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
    */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @var Provider
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider", inversedBy="newsList")
     * @ORM\JoinColumn(name="id_provider", referencedColumnName="id")
    */
    private $provider;

    public function __construct()
    {
        $this->dateCreatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreatedAt(): \DateTime
    {
        return $this->dateCreatedAt;
    }

    /**
     * @param \DateTime $dateCreatedAt
     */
    public function setDateCreatedAt(\DateTime $dateCreatedAt): void
    {
        $this->dateCreatedAt = $dateCreatedAt;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}
