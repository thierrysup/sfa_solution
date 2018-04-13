<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Survey
 *
 * @ORM\Table(name="survey")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\SurveyRepository")
 */
class Survey
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_submit", type="date")
     */
    private $dateSubmit;

    /**
     * @var string
     *
     * @ORM\Column(name="commit", type="string", length=255)
     */
    private $commit;

    /**
     * @var string
     *
     * @ORM\Column(name="actor_name", type="string", length=255)
     */
    private $actorName;

    /**
     * @var string
     *
     * @ORM\Column(name="actor_phone", type="string", length=255)
     */
    private $actorPhone;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var float
     *
     * @ORM\Column(name="lattitude", type="float")
     */
    private $lattitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;


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
     * Set dateSubmit
     *
     * @param \DateTime $dateSubmit
     *
     * @return Survey
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
     * Set commit
     *
     * @param string $commit
     *
     * @return Survey
     */
    public function setCommit($commit)
    {
        $this->commit = $commit;

        return $this;
    }

    /**
     * Get commit
     *
     * @return string
     */
    public function getCommit()
    {
        return $this->commit;
    }

    /**
     * Set actorName
     *
     * @param string $actorName
     *
     * @return Survey
     */
    public function setActorName($actorName)
    {
        $this->actorName = $actorName;

        return $this;
    }

    /**
     * Get actorName
     *
     * @return string
     */
    public function getActorName()
    {
        return $this->actorName;
    }

    /**
     * Set actorPhone
     *
     * @param string $actorPhone
     *
     * @return Survey
     */
    public function setActorPhone($actorPhone)
    {
        $this->actorPhone = $actorPhone;

        return $this;
    }

    /**
     * Get actorPhone
     *
     * @return string
     */
    public function getActorPhone()
    {
        return $this->actorPhone;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Survey
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
     * Set lattitude
     *
     * @param float $lattitude
     *
     * @return Survey
     */
    public function setLattitude($lattitude)
    {
        $this->lattitude = $lattitude;

        return $this;
    }

    /**
     * Get lattitude
     *
     * @return float
     */
    public function getLattitude()
    {
        return $this->lattitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Survey
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}

