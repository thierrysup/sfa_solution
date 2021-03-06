<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation as Serializer;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ProductRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Product
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
     * @ORM\Column(name="groupable", type="boolean")
     * @Type("boolean")
     */
    private $groupable;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Type("int")
     */
    private $quantity;

    /**
     * @var bool
     *
     * @ORM\Column(name="freeUse", type="boolean")
     * @Type("boolean")
     */
    private $freeUse;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     * @Type("boolean")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="date")
     * @jms\Serializer\Annotation\Type("DateTime<'Y-m-d'>")
     */
    private $dateCreate;

    /**
     * @var Activity The activity this product is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Activity", inversedBy="products",cascade={"persist"})
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Activity")
     * @MaxDepth(1)
     */
    private $activity;

    /**
     * @var Product[] Available targets for this product.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Target", mappedBy="product",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\Target>")
     * @Exclude
     */
    private $targets;

    /**
     * @var ProductSurvey[] Available productsurveys for this product.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\ProductSurvey", mappedBy="product",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\ProductSurvey>")
     * @Exclude
     */
    private $productsurveys;

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
     * @return Product
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
     * Set groupable
     *
     * @param boolean $groupable
     *
     * @return Product
     */
    public function setGroupable($groupable)
    {
        $this->groupable = $groupable;

        return $this;
    }

    /**
     * Get groupable
     *
     * @return bool
     */
    public function getGroupable()
    {
        return $this->groupable;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set freeUse
     *
     * @param boolean $freeUse
     *
     * @return Product
     */
    public function setFreeUse($freeUse)
    {
        $this->freeUse = $freeUse;

        return $this;
    }

    /**
     * Get freeUse
     *
     * @return bool
     */
    public function getFreeUse()
    {
        return $this->freeUse;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Product
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Product
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->targets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productsurveys = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set activity
     *
     * @param \ApiBundle\Entity\Activity $activity
     *
     * @return Product
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

    /**
     * Add target
     *
     * @param \ApiBundle\Entity\Target $target
     *
     * @return Product
     */
    public function addTarget(\ApiBundle\Entity\Target $target)
    {
        $this->targets[] = $target;

        return $this;
    }

    /**
     * Remove target
     *
     * @param \ApiBundle\Entity\Target $target
     */
    public function removeTarget(\ApiBundle\Entity\Target $target)
    {
        $this->targets->removeElement($target);
    }

    /**
     * Get targets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * Add productsurvey
     *
     * @param \ApiBundle\Entity\ProductSurvey $productsurvey
     *
     * @return Product
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
}
