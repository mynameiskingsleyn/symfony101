<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email",message="This -email is already used")
 * @UniqueEntity(fields="username",message="This username is already used")
 */
class User implements UserInterface, \Serializable
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=6,max=4096)
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
    * @Assert\NotBlank()
    * @Assert\Length(min=6,max=4096)
    */
    private $plainPassword;
    /**
     * @ORM\Column(type="string",length=254, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string",length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=6,max=50)
     */
    private $fullName;
    /**
    * @ORM\OneToMany(targetEntity="App\Entity\MicroPost",mappedBy="user")
    */
    private $posts;

    /**
    * @var array
    * @ORM\Column(type="simple_array")
    */
    private $roles;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User",mappedBy="following")
    */
    private $followers;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User",mappedBy="followers")
    */
    private $following;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
    * @param mixed $fullName
    */
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
    * @param mixed $fullName
    */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
    * @param mixed $username
    */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
    * @param mixed $password
    */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
    * @param mixed $plainPassword
    */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
    /**
    * @param mixed $plainPassword
    */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    /**
    * @param mixed $email
    */
    public function setEmail($email):void
    {
        $this->email = $email;
    }

    /**
    * @param mixed $email
    */
    public function getEmail()
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles()
    {
        // return[
        //   ROLE_USER,
        //   ROLE_ADMIN
        // ];
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPosts()
    {
        return $this->posts;
    }
    public function eraseCredentials()
    {
    }
    public function serialize()
    {
        return serialize([
        $this->id,
        $this->username,
        $this->password,
      ]);
    }

    public function unserialize($serialized)
    {
        list(
        $this->id,
        $this->username,
        $this->password,
      ) = unserialize($serialized);
    }
}
