<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation as Serializer;

/**
 * ActivityUser
 *
 * @ORM\Table(name="activity_user")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ActivityUserRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class ActivityUser
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
     * @ORM\Column(name="editAuth", type="boolean")
     * @Type("boolean")
     */
    private $editAuth;

    /**
     * @var bool
     *
     * @ORM\Column(name="createAuth", type="boolean")
     * @Type("boolean")
     */
    private $createAuth;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleteAuth", type="boolean")
     * @Type("boolean")
     */
    private $deleteAuth;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_submit", type="date")
     * @jms\Serializer\Annotation\Type("DateTime<'Y-m-d'>")
     */
    private $dateSubmit;

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
     * 1 POS
     * 2 Quarter
     * 3 Zone
     * 4 Town
     * 5 Region
     * 6 Country
     *
     * @ORM\Column(name="zoneInfluence", type="integer")
     * @Type("int")
     */
    private $zoneInfluence;

    /**
     * @var int
     *
     * 1 operational
     * 2 office
     *
     * @ORM\Column(name="mobility", type="integer")
     * @Type("int")
     */
    private $mobility;

    /**
     * @var POS The pos this activityUser is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\POS", inversedBy="activityUsers",cascade={"persist"})
     * @ORM\JoinColumn(name="pos_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\POS")
     * @MaxDepth(1)
     */
    private $pos;

    /**
     * @var Town The town this activityUser is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Town", inversedBy="activityUsers",cascade={"persist"})
     * @ORM\JoinColumn(name="town_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Town")
     * @MaxDepth(1)
     */
    private $town;

    /**
     * @var Quarter The quarter this activityUser is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Quarter", inversedBy="activityUsers",cascade={"persist"})
     * @ORM\JoinColumn(name="quarter_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Quarter")
     * @MaxDepth(1)
     */
    private $quarter;

    /**
     * @var Sector The sector this activityUser is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Sector", inversedBy="activityUsers",cascade={"persist"})
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Sector")
     * @MaxDepth(0)
     */
    private $sector;

    /**
     * @var Region The region this activityUser is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Region", inversedBy="activityUsers",cascade={"persist"})
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Region")
     * @MaxDepth(1)
     */
    private $region;

    /**
     * @var Country The country this activityUser is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Country", inversedBy="activityUsers",cascade={"persist"})
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Country")
     * @MaxDepth(1)
     */
    private $country;

    /**
     * @var Role The role this activityUser is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Role", inversedBy="activityUsers",cascade={"persist"})
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Role")
     * @MaxDepth(1)
     */
    private $role;

    /**
     * @var User The user this activityUser is about.
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="activityUsers",cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("AppBundle\Entity\User")
     * @MaxDepth(1)
     * @Exclude
     */
    private $user;

    /**
     * @var Activity The activity this activityUser is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Activity", inversedBy="activityUsers",cascade={"persist"})
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Activity")
     * @MaxDepth(1)
     * @Exclude
     */
    private $activity;


    public function __toString() {
        return $this->getActivity()->getName()+" "+$this->getUser()->getUsername();
    }

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
     * Set editAuth
     *
     * @param boolean $editAuth
     *
     * @return ActivityUser
     */
    public function setEditAuth($editAuth)
    {
        $this->editAuth = $editAuth;

        return $this;
    }

    /**
     * Get editAuth
     *
     * @return bool
     */
    public function getEditAuth()
    {
        return $this->editAuth;
    }

    /**
     * Set createAuth
     *
     * @param boolean $createAuth
     *
     * @return ActivityUser
     */
    public function setCreateAuth($createAuth)
    {
        $this->createAuth = $createAuth;

        return $this;
    }

    /**
     * Get createAuth
     *
     * @return bool
     */
    public function getCreateAuth()
    {
        return $this->createAuth;
    }

    /**
     * Set deleteAuth
     *
     * @param boolean $deleteAuth
     *
     * @return ActivityUser
     */
    public function setDeleteAuth($deleteAuth)
    {
        $this->deleteAuth = $deleteAuth;

        return $this;
    }

    /**
     * Get deleteAuth
     *
     * @return bool
     */
    public function getDeleteAuth()
    {
        return $this->deleteAuth;
    }

    /**
     * Set dateSubmit
     *
     * @param \DateTime $dateSubmit
     *
     * @return ActivityUser
     */
    public function setDateSubmit($dateSubmit)
    {
        $this->dateSubmit = $dateSubmit;

        return $this;
    }

    /**
     * Get dateSubmit
     *
     * @return \DateTime
     */
    public function getDateSubmit()
    {
        return $this->dateSubmit;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return ActivityUser
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
     * Set zoneInfluence
     *
     * @param integer $zoneInfluence
     *
     * @return ActivityUser
     */
    public function setZoneInfluence($zoneInfluence)
    {
        $this->zoneInfluence = $zoneInfluence;

        return $this;
    }

    /**
     * Get zoneInfluence
     *
     * @return integer
     */
    public function getZoneInfluence()
    {
        return $this->zoneInfluence;
    }

    /**
     * Set mobility
     *
     * @param integer $mobility
     *
     * @return ActivityUser
     */
    public function setMobility($mobility)
    {
        $this->mobility = $mobility;

        return $this;
    }

    /**
     * Get mobility
     *
     * @return integer
     */
    public function getMobility()
    {
        return $this->mobility;
    }

    /**
     * Set pos
     *
     * @param \ApiBundle\Entity\POS $pos
     *
     * @return ActivityUser
     */
    public function setPos(\ApiBundle\Entity\POS $pos = null)
    {
        $this->pos = $pos;

        return $this;
    }

    /**
     * Get pos
     *
     * @return \ApiBundle\Entity\POS
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Set town
     *
     * @param \ApiBundle\Entity\Town $town
     *
     * @return ActivityUser
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

    /**
     * Set quarter
     *
     * @param \ApiBundle\Entity\Quarter $quarter
     *
     * @return ActivityUser
     */
    public function setQuarter(\ApiBundle\Entity\Quarter $quarter = null)
    {
        $this->quarter = $quarter;

        return $this;
    }

    /**
     * Get quarter
     *
     * @return \ApiBundle\Entity\Quarter
     */
    public function getQuarter()
    {
        return $this->quarter;
    }

    /**
     * Set sector
     *
     * @param \ApiBundle\Entity\Sector $sector
     *
     * @return ActivityUser
     */
    public function setSector(\ApiBundle\Entity\Sector $sector = null)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \ApiBundle\Entity\Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set region
     *
     * @param \ApiBundle\Entity\Region $region
     *
     * @return ActivityUser
     */
    public function setRegion(\ApiBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \ApiBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set country
     *
     * @param \ApiBundle\Entity\Country $country
     *
     * @return ActivityUser
     */
    public function setCountry(\ApiBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \ApiBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set role
     *
     * @param \ApiBundle\Entity\Role $role
     *
     * @return ActivityUser
     */
    public function setRole(\ApiBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \ApiBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return ActivityUser
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set activity
     *
     * @param \ApiBundle\Entity\Activity $activity
     *
     * @return ActivityUser
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
