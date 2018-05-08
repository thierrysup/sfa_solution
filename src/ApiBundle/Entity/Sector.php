<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation as Serializer;

/**
 * Sector
 *
 * @ORM\Table(name="sector")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\SectorRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Sector
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=15)
     * @Type("string")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Type("string")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     * @Type("boolean")
     */
    private $status;

    /**
     * @var ActivityUser[] Available ActivityUsers for this sector.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\ActivityUser", mappedBy="sector",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\ActivityUser>")
     * @Exclude
     */
    private $activityUsers;

    /**
     * @var Quarter[] Available quarter for this sector.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Quarter", mappedBy="sector",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\Quarter>")
     * @Exclude
     */
    private $quarters;

    /**
     * @var Town The town this sector is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Town", inversedBy="sectors",cascade={"persist"})
     * @ORM\JoinColumn(name="town_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Town")
     * @MaxDepth(1)
     */
    private $town;


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
     * @return Sector
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
     * Set code
     *
     * @param string $code
     *
     * @return Sector
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Sector
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Sector
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
     * @return Sector
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
     * Add quarter
     *
     * @param \ApiBundle\Entity\Quarter $quarter
     *
     * @return Sector
     */
    public function addQuarter(\ApiBundle\Entity\Quarter $quarter)
    {
        $this->quarters[] = $quarter;

        return $this;
    }

    /**
     * Remove quarter
     *
     * @param \ApiBundle\Entity\Quarter $quarter
     */
    public function removeQuarter(\ApiBundle\Entity\Quarter $quarter)
    {
        $this->quarters->removeElement($quarter);
    }

    /**
     * Get quarters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuarters()
    {
        return $this->quarters;
    }

    /**
     * Set town
     *
     * @param \ApiBundle\Entity\Town $town
     *
     * @return Sector
     */
    public function setTown(\ApiBundle\Entity\Town $town = null)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return \ApiBundle\Entity\Town
     */
    public function getTown()
    {
        return $this->town;
    }
}
