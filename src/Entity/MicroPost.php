<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * //@Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="string", length=280)
    * @Assert\NotBlank()
    * @Assert\Length(min=10,minMessage="body must be atleast 10 characters long")
    */
    private $text;

    /**
    * @ORM\Column(type="datetime")
    */
    private $created_at;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="posts")
    * @ORM\JoinColumn(nullable=false) // can also have (fieldName="user_id")
    */
    private $user;

    /**
    * @ORM\ManyToMany(targetEntity="App\Entity\User",inversedBy="postsLiked")
    * @ORM\JoinTable(name="post_likes",
    *     joinColumns={ @ORM\JoinColumn(name="post_id",referencedColumnName="id")},
    *     inverseJoinColumns={ @ORM\JoinColumn(name="user_id",referencedColumnName="id")}
    * )
    */
    private $likedBy;

    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }
    /**
    * @param mixed $time
    */
    public function setCreatedAt($time)
    {
        $this->created_at = $time;
    }
    /**
    * @ORM\PrePersist()
    */
    public function setCreatedAtOnPersist():void
    {
        $this->created_at = new \DateTime();
    }

    /**
    * @return mixed
    */
    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    /**
    * @param mixed $user
    */
    public function setUser(User $user) : void
    {
        $this->user = $user;
    }
    /** @return User */
    public function getUser()
    {
        return $this->user;
    }
    /**
    * @return Collection
    */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    public function like(User $user)
    {
        if ($this->getLikedBy()->contains($user)) {
            return;
        }
        $this->getLikedBy()->add($user);
    }
}
