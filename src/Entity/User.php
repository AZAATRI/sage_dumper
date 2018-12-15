<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 */
class User implements UserInterface
{
    const ROLES = [
        'admin' => 'ROLE_ADMIN',
        'commercial' => 'ROLE_COMMERCIAL'
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Length(min="2",minMessage="Minimum 2 caracteres")
     * @Assert\Length(max="80",maxMessage="Maximum 80 caracteres")
     *
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Length(min="6",minMessage="Le mot de passe doit faire minimum 6 caracteres")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255,nullable=false)
     */
    private $role_matcher;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoleMatcher(): ?string
    {
        return $this->role_matcher;
    }

    public function setRoleMatcher(string $role_matcher): self
    {
        $this->role_matcher = $role_matcher;

        return $this;
    }


    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        return [self::ROLES[$this->role_matcher] ?? null];
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


}
