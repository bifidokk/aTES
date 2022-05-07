<?php

namespace Task\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass="Task\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator")
     */
    protected string $id = '';

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    protected string $email = '';

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected string $name = '';

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?\DateTime $updatedAt;

    /**
     * @ORM\Column(type="string", length=36)
     */
    protected string $publicId = '';

    /**
     * @ORM\Column(type="json")
     */
    protected array $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="owner", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    protected Collection $ownedTasks;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="assignee", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    protected Collection $assignedTasks;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->publicId = Uuid::v4()->toRfc4122();
        $this->ownedTasks = new ArrayCollection();
        $this->assignedTasks = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param string $publicId
     */
    public function setPublicId(string $publicId): void
    {
        $this->publicId = $publicId;
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
