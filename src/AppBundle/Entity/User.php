<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation as Serializer;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Type("int")
     */
    protected $id;

    /**
     * @var int
     *
     * 1 client
     * 2 employ
     *
     * @ORM\Column(name="type_user", type="integer")
     * @Type("int")
     */
    protected $type_user;

    /**
     * @var ActivityUser[] Available ActivityUsers for this user.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\ActivityUser", mappedBy="user",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\ActivityUser>")
     */
    protected $activityUsers;

     /**
     * Many Users have Many Users.
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="mySupervisors")
     * @Type("ArrayCollection<AppBundle\Entity\User>")
     */
    private $supervisesWithMe;

    /**
     * Many Users have many Users.
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="supervisesWithMe")
     * @ORM\JoinTable(name="managers",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="supervisor_user_id", referencedColumnName="id")}
     *      )
     * @Type("ArrayCollection<AppBundle\Entity\User>")
     */
    private $mySupervisors;

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
     * Set typeUser
     *
     * @param integer $typeUser
     *
     * @return User
     */
    public function setTypeUser($typeUser)
    {
        $this->type_user = $typeUser;

        return $this;
    }

    /**
     * Get typeUser
     *
     * @return integer
     */
    public function getTypeUser()
    {
        return $this->type_user;
    }

    /**
     * Add activityUser
     *
     * @param \ApiBundle\Entity\ActivityUser $activityUser
     *
     * @return User
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

    /**
     * Add supervisesWithMe
     *
     * @param \AppBundle\Entity\User $supervisesWithMe
     *
     * @return User
     */
    public function addSupervisesWithMe(\AppBundle\Entity\User $supervisesWithMe)
    {
        $this->supervisesWithMe[] = $supervisesWithMe;

        return $this;
    }

    /**
     * Remove supervisesWithMe
     *
     * @param \AppBundle\Entity\User $supervisesWithMe
     */
    public function removeSupervisesWithMe(\AppBundle\Entity\User $supervisesWithMe)
    {
        $this->supervisesWithMe->removeElement($supervisesWithMe);
    }

    /**
     * Get supervisesWithMe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSupervisesWithMe()
    {
        return $this->supervisesWithMe;
    }

    /**
     * Add mySupervisor
     *
     * @param \AppBundle\Entity\User $mySupervisor
     *
     * @return User
     */
    public function addMySupervisor(\AppBundle\Entity\User $mySupervisor)
    {
        $this->mySupervisors[] = $mySupervisor;

        return $this;
    }

    /**
     * Remove mySupervisor
     *
     * @param \AppBundle\Entity\User $mySupervisor
     */
    public function removeMySupervisor(\AppBundle\Entity\User $mySupervisor)
    {
        $this->mySupervisors->removeElement($mySupervisor);
    }

    /**
     * Get mySupervisors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMySupervisors()
    {
        return $this->mySupervisors;
    }
}
