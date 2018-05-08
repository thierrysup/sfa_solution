<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude ;
use JMS\Serializer\Annotation as Serializer;

/**
 * Activity
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ActivityRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Activity
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
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date")
     * @jms\Serializer\Annotation\Type("DateTime<'Y-m-d'>")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date")
     * @jms\Serializer\Annotation\Type("DateTime<'Y-m-d'>")
     */
    private $endDate;

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
     * product 1
     * service 0
     *
     * @ORM\Column(name="typeActivity", type="integer")
     * @Type("int")
     */
    private $type_activity;

    /**
     * @var Entreprise The entreprise this activity is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Entreprise", inversedBy="activities",cascade={"persist"})
     * @ORM\JoinColumn(name="entreprise_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Entreprise")
     * @MaxDepth(1)
     */
    private $entreprise;

    /**
     * @var Product[] Available products for this activity.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Product", mappedBy="activity",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\Product>")
     * @MaxDepth(1)
     * @Exclude
     */
    private $products;

    /**
     * @var ActivityUser[] Available ActivityUsers for this activity.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\ActivityUser", mappedBy="activity",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\ActivityUser>")
     * @MaxDepth(1) 
     * @Exclude
     */
    private $activityUsers;

    public function __toString() {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     *
     * @return Activity
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Activity
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Activity
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Activity
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
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activityUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set typeActivity
     *
     * @param integer $typeActivity
     *
     * @return Activity
     */
    public function setTypeActivity($typeActivity)
    {
        $this->type_activity = $typeActivity;

        return $this;
    }

    /**
     * Get typeActivity
     *
     * @return integer
     */
    public function getTypeActivity()
    {
        return $this->type_activity;
    }

    /**
     * Set entreprise
     *
     * @param \ApiBundle\Entity\Entreprise $entreprise
     *
     * @return Activity
     */
    public function setEntreprise(\ApiBundle\Entity\Entreprise $entreprise = null)
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * Get entreprise
     *
     * @return \ApiBundle\Entity\Entreprise
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * Add product
     *
     * @param \ApiBundle\Entity\Product $product
     *
     * @return Activity
     */
    public function addProduct(\ApiBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \ApiBundle\Entity\Product $product
     */
    public function removeProduct(\ApiBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add activityUser
     *
     * @param \ApiBundle\Entity\ActivityUser $activityUser
     *
     * @return Activity
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
