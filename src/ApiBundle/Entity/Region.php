<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation as Serializer;
/**
 * Region
 *
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\RegionRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Region
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
     * @var Product[] Available targets for this product.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Target", mappedBy="product",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\Target>")
     * @MaxDepth(1)
     */
    private $targets;

    /**
     * @var ActivityUser[] Available ActivityUsers for this region.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\ActivityUser", mappedBy="region",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\ActivityUser>")
     * @MaxDepth(1)
     */
    private $activityUsers;

     /**
     * @var Town[] Available town for this region.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Town", mappedBy="region",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\Town>")
     * @MaxDepth(1)
     */
    private $towns;


    /**
     * @var Country The country this region is about.
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Country", inversedBy="regions",cascade={"persist"})
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     * @Type("ApiBundle\Entity\Country")
     * @MaxDepth(1)
     */
    private $country;

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
     * @return Region
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
     * @return Region
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
     * @return Region
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
        $this->targets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activityUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add target
     *
     * @param \ApiBundle\Entity\Target $target
     *
     * @return Region
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
     * Add activityUser
     *
     * @param \ApiBundle\Entity\ActivityUser $activityUser
     *
     * @return Region
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
     * Add town
     *
     * @param \ApiBundle\Entity\Town $town
     *
     * @return Region
     */
    public function addTown(\ApiBundle\Entity\Town $town)
    {
        $this->towns[] = $town;

        return $this;
    }

    /**
     * Remove town
     *
     * @param \ApiBundle\Entity\Town $town
     */
    public function removeTown(\ApiBundle\Entity\Town $town)
    {
        $this->towns->removeElement($town);
    }

    /**
     * Get towns
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTowns()
    {
        return $this->towns;
    }

    /**
     * Set country
     *
     * @param \ApiBundle\Entity\Country $country
     *
     * @return Region
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
}
