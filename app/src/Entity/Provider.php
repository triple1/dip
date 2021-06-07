<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProviderRepository::class)
 */
class Provider
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
     * @ORM\Column(type="string", length=200, unique=true)
    */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $phone;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
    */
    private $userCreated;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Participant", inversedBy="providers")
     * @ORM\JoinTable(name="providers_participants")
    */
    private $participants;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Service", inversedBy="providers")
     * @ORM\JoinTable(name="providers_services")
     */
    private $services;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\News", mappedBy="provider")
    */
    private $newsList;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=200, nullable=true)
    */
    private $imgIconName;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="provider")
    */
    private $users;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->newsList = new ArrayCollection();
        $this->users = new ArrayCollection();
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
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param Collection $users
     */
    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }

    public function addUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setProvider($this);
        }
    }

    /**
     * @return string|null
     */
    public function getImgIconName(): ?string
    {
        return $this->imgIconName;
    }

    /**
     * @param string|null $imgIconName
     */
    public function setImgIconName(?string $imgIconName): void
    {
        $this->imgIconName = $imgIconName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return Collection
     */
    public function getNewsList()
    {
        return $this->newsList;
    }

    /**
     * @param Collection $newsList
     */
    public function setNewsList($newsList): void
    {
        $this->newsList = $newsList;
    }

    public function addNews(News $news): void
    {
        if (!$this->newsList->contains($news)) {
            $this->newsList->add($news);
            $news->setProvider($this);
        }
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
        $this->services = new ArrayCollection();
        foreach ($services as $service) $this->addService($service);
    }

    public function addService(Service $service): void
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->addProvider($this);
        }
    }

    /**
     * @return Collection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param Collection $participants
     */
    public function setParticipants(array $participants): void
    {
        $this->participants = new ArrayCollection($participants);
    }

    public function addParticipant(Participant $participant): void
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return User
     */
    public function getUserCreated(): User
    {
        return $this->userCreated;
    }

    /**
     * @param User $userCreated
     */
    public function setUserCreated(User $userCreated): void
    {
        $this->userCreated = $userCreated;
    }
}
