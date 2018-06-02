<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation as Serializer;

/**
 * ProductSurvey
 *
 * @ORM\Table(name="product_survey")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ProductSurveyRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class ProductSurvey
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Type("int")
     */
    private $quantity;

    /**
     * @var bool
     *
     * @ORM\Column(name="baseline", type="boolean")
     * @Type("boolean")
     */
    private $baseline;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_submit", type="date")
     * @jms\Serializer\Annotation\Type("DateTime<'Y-m-d'>")
     */
    private $dateSubmit;

    /**
     * @var int
     *
     * @ORM\Column(name="quantityIn", type="integer", nullable=true)
     * @Type("int")
     */
    private $quantityIn;

    /**
     * @var string
     *
     * @ORM\Column(name="commit", type="string", length=255, nullable=true)
     * @Type("string")
     */
    private $commit;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     * @Type("boolean")
     */
    private $status;

     /**
     * @var Product The product this productSurvey is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Product", inversedBy="productSurveys",cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Product")
     * @MaxDepth(1)
     */
    private $product;


     /**
     * @var Survey The Survey this productSurvey is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Survey", inversedBy="productSurveys",cascade={"persist"})
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Survey")
     * @MaxDepth(1)
     */
    private $survey;

    public function __toString() {
        return $this->getId();
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return ProductSurvey
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
     * Set dateSubmit
     *
     * @param \DateTime $dateSubmit
     *
     * @return ProductSurvey
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
     * Set quantityIn
     *
     * @param integer $quantityIn
     *
     * @return ProductSurvey
     */
    public function setQuantityIn($quantityIn)
    {
        $this->quantityIn = $quantityIn;

        return $this;
    }

    /**
     * Get quantityIn
     *
     * @return int
     */
    public function getQuantityIn()
    {
        return $this->quantityIn;
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
     * Set status
     *
     * @param boolean $status
     *
     * @return ProductSurvey
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
     * Set product
     *
     * @param \ApiBundle\Entity\Product $product
     *
     * @return ProductSurvey
     */
    public function setProduct(\ApiBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \ApiBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set survey
     *
     * @param \ApiBundle\Entity\Survey $survey
     *
     * @return ProductSurvey
     */
    public function setSurvey(\ApiBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey
     *
     * @return \ApiBundle\Entity\Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Get the value of baseline
     *
     * @return  bool
     */ 
    public function getBaseline()
    {
        return $this->baseline;
    }

    /**
     * Set the value of baseline
     *
     * @param  bool  $baseline
     *
     * @return  self
     */ 
    public function setBaseline(bool $baseline)
    {
        $this->baseline = $baseline;

        return $this;
    }
}
