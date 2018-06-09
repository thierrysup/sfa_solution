<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation as Serializer;

/**
 * UserManager
 *
 * @ORM\Table(name="user_manager")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserManagerRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class UserManager
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
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     * @Type("boolean")
     */
    private $status;

    /**
     * @var User The manager this userManager is about.
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id",nullable=true)
     * @Type("AppBundle\Entity\User")
     * @MaxDepth(1)
     */
    private $manager;

    /**
     * @var User The manager this userManager is about.
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="subordinate_id", referencedColumnName="id",nullable=true)
     * @Type("AppBundle\Entity\User")
     * @MaxDepth(1)
     */
    private $subordinate;

    /**
     * @var Activity The activity this userManagement is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Activity")
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id",nullable=true)
     * @Type("ApiBundle\Entity\Activity")
     * @MaxDepth(1)
     */
    private $activity;



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
     * Set status
     *
     * @param boolean $status
     *
     * @return UserManager
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set manager
     *
     * @param \AppBundle\Entity\User $manager
     *
     * @return UserManager
     */
    public function setManager(\AppBundle\Entity\User $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return \AppBundle\Entity\User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set subordinate
     *
     * @param \AppBundle\Entity\User $subordinate
     *
     * @return UserManager
     */
    public function setSubordinate(\AppBundle\Entity\User $subordinate = null)
    {
        $this->subordinate = $subordinate;

        return $this;
    }

    /**
     * Get subordinate
     *
     * @return \AppBundle\Entity\User
     */
    public function getSubordinate()
    {
        return $this->subordinate;
    }

    /**
     * Set activity
     *
     * @param \ApiBundle\Entity\Activity $activity
     *
     * @return UserManager
     */
    public function setActivity(\ApiBundle\Entity\Activity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \ApiBundle\Entity\Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
