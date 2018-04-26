<?php

namespace ApiBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation as Serializer;

/**
 * Survey
 *
 * @ORM\Table(name="survey")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\SurveyRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Survey
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_submit", type="date")
     * @jms\Serializer\Annotation\Type("DateTime<'Y-m-d'>")
     */
    private $dateSubmit;

    /**
     * @var string
     *
     * @ORM\Column(name="commit", type="string", length=255)
     * @Type("string")
     */
    private $commit;

    /**
     * @var string
     *
     * @ORM\Column(name="actor_name", type="string", length=255)
     * @Type("string")
     */
    private $actorName;

    /**
     * @var string
     *
     * @ORM\Column(name="actor_phone", type="string", length=255)
     * @Type("string")
     */
    private $actorPhone;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     * @Type("boolean")
     */
    private $status;

    /**
     * @var float
     *
     * @ORM\Column(name="lattitude", type="float")
     * @Type("float")
     */
    private $lattitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @Type("float")
     */
    private $longitude;

    /**
     * @var ProductSurvey[] Available productsurveys for this product.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\ProductSurvey", mappedBy="survey",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\ProductSurvey>")
     */
    private $productsurveys;


    /**
     * @var Quarter The quarter this survey is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Quarter", inversedBy="surveys",cascade={"persist"})
     * @ORM\JoinColumn(name="quarter_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Quarter")
     * @MaxDepth(1)
     */
    private $quarter;

    /**
     * @var User The user this survey is about.
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=true)
     * @Type("AppBundle\Entity\User")
     * @MaxDepth(1)
     */
    private $user;

    /**
     * @var POS The pos this pos is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\POS", inversedBy="surveys",cascade={"persist"})
     * @ORM\JoinColumn(name="pos_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\POS")
     * @MaxDepth(1)
     */
    private $pos;

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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productsurveys = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add productsurvey
     *
     * @param \ApiBundle\Entity\ProductSurvey $productsurvey
     *
     * @return Survey
     */
    public function addProductsurvey(\ApiBundle\Entity\ProductSurvey $productsurvey)
    {
        $this->productsurveys[] = $productsurvey;

        return $this;
    }

    /**
     * Remove productsurvey
     *
     * @param \ApiBundle\Entity\ProductSurvey $productsurvey
     */
    public function removeProductsurvey(\ApiBundle\Entity\ProductSurvey $productsurvey)
    {
        $this->productsurveys->removeElement($productsurvey);
    }

    /**
     * Get productsurveys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductsurveys()
    {
        return $this->productsurveys;
    }

    /**
     * Set quarter
     *
     * @param \ApiBundle\Entity\Quarter $quarter
     *
     * @return Survey
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
     * Set pos
     *
     * @param \ApiBundle\Entity\POS $pos
     *
     * @return Survey
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
}
