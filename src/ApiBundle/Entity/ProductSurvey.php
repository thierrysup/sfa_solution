<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductSurvey
 *
 * @ORM\Table(name="product_survey")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ProductSurveyRepository")
 */
class ProductSurvey
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_submit", type="date")
     */
    private $dateSubmit;

    /**
     * @var int
     *
     * @ORM\Column(name="quantityIn", type="integer")
     */
    private $quantityIn;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;


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
}

