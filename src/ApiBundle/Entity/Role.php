<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation as Serializer;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\RoleRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Role
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Type("int")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Type("string")
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     * @Type("boolean")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer")
     * @Type("int")
     */
    private $priority;

    /**
     * @var ActivityUser[] Available ActivityUsers for this role.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\ActivityUser", mappedBy="role",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\ActivityUser>")
     * @Exclude
     */
    private $activityUsers;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Role
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return Role
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->activityUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add activityUser
     *
     * @param \ApiBundle\Entity\ActivityUser $activityUser
     *
     * @return Role
     */
    public function addActivityUser(\ApiBundle\Entity\ActivityUser $activityUser)
    {
        $this->activityUsers[] = $activityUser;

        return $this;
    }

    /**
     * Remove activityUser
     *
     * @param \ApiBundle\Entity\ActivityUser $activityUser
     */
    public function removeActivityUser(\ApiBundle\Entity\ActivityUser $activityUser)
    {
        $this->activityUsers->removeElement($activityUser);
    }

    /**
     * Get activityUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivityUsers()
    {
        return $this->activityUsers;
    }
}
