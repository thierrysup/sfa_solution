<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation as Serializer;

/**
 * Quarter
 *
 * @ORM\Table(name="quarter")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\QuarterRepository")
 *  @Serializer\ExclusionPolicy("none")
 */
class Quarter
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
     * @var Quarter[] Available Surveys for this Quarter.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Survey", mappedBy="quarter",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\Survey>")
     * @Exclude
     */
    private $surveys;

    /**
     * @var POS[] Available pos for this Quarter.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\POS", mappedBy="quarter",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\POS>")
     * @Exclude
     */
    private $poss;

    /**
     * @var ActivityUser[] Available ActivityUsers for this quarter.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\ActivityUser", mappedBy="quarter",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\ActivityUser>")
     * @Exclude
     */
    private $activityUsers;

    /**
     * @var Sector The sector this quarter is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Sector", inversedBy="quarters",cascade={"persist"})
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Sector")
     * @MaxDepth(0)
     */
    private $sector;

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
     * @return Quarter
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
     * Set description
     *
     * @param string $description
     *
     * @return Quarter
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
     * @return Quarter
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
        $this->surveys = new \Doctrine\Common\Collections\ArrayCollection();
        $this->poss = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activityUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add survey
     *
     * @param \ApiBundle\Entity\Survey $survey
     *
     * @return Quarter
     */
    public function addSurvey(\ApiBundle\Entity\Survey $survey)
    {
        $this->surveys[] = $survey;

        return $this;
    }

    /**
     * Remove survey
     *
     * @param \ApiBundle\Entity\Survey $survey
     */
    public function removeSurvey(\ApiBundle\Entity\Survey $survey)
    {
        $this->surveys->removeElement($survey);
    }

    /**
     * Get surveys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveys()
    {
        return $this->surveys;
    }

    /**
     * Add poss
     *
     * @param \ApiBundle\Entity\POS $poss
     *
     * @return Quarter
     */
    public function addPoss(\ApiBundle\Entity\POS $poss)
    {
        $this->poss[] = $poss;

        return $this;
    }

    /**
     * Remove poss
     *
     * @param \ApiBundle\Entity\POS $poss
     */
    public function removePoss(\ApiBundle\Entity\POS $poss)
    {
        $this->poss->removeElement($poss);
    }

    /**
     * Get poss
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPoss()
    {
        return $this->poss;
    }

    /**
     * Add activityUser
     *
     * @param \ApiBundle\Entity\ActivityUser $activityUser
     *
     * @return Quarter
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
     * Set sector
     *
     * @param \ApiBundle\Entity\Sector $sector
     *
     * @return Quarter
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
}
