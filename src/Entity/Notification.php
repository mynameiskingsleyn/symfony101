<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"like"="LikeNotification"})
 */
abstract class Notification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User")
    */
    private $user;

    /**
    * @ORM\Column(type="boolean")
    */
    private $seen;

    public function __construct()
    {
        $this->seen = false;
    }
    /**
    * @return mixed
    */
    public function getId()
    {
        return $this->id;
    }
    /**
    * @return boolean
    */
    public function getSeen(): boolean
    {
        return $this->seen;
    }

    /**
    * @param boolean $seen
    */
    public function setSeen($seen): void
    {
        $this->seen = $seen;
    }

    /**
    * @return mixed
    */
    public function getUser()
    {
        return $this->user;
    }

    /**
    * @param mixed $user
    */
    public function setUser($user): void
    {
        $this->user = $user;
    }
}
