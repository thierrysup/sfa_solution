<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * Entreprise
 *
 * @ORM\Table(name="entreprise")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\EntrepriseRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Entreprise
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
     * @ORM\Column(name="adresse", type="string", length=255)
     * @Type("string")
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="pobox", type="string", length=255)
     * @Type("string")
     */
    private $pobox;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     * @Type("string")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Type("string")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="colorStyle", type="string", length=255)
     * @Serializer\SerializedName("colorStyle")
     * @Type("string")
     */
    private $colorStyle;

    /**
     * @var string
     * @Assert\Image()
     * @ORM\Column(name="logoURL", type="string", length=255)
     * @Serializer\SerializedName("logoURL")
     * @Type("string")
     */
    private $logoURL;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     * @Type("boolean")
     */
    private $status;

    /**
     * @var Activity[] Available activities for this entreprise.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Activity", mappedBy="entreprise",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\Activity>")
     * @Exclude
     */
    private $activities;


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
     * @return Entreprise
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Entreprise
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set pobox
     *
     * @param string $pobox
     *
     * @return Entreprise
     */
    public function setPobox($pobox)
    {
        $this->pobox = $pobox;

        return $this;
    }

    /**
     * Get pobox
     *
     * @return string
     */
    public function getPobox()
    {
        return $this->pobox;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Entreprise
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Entreprise
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
     * Set colorStyle
     *
     * @param string $colorStyle
     *
     * @return Entreprise
     */
    public function setColorStyle($colorStyle)
    {
        $this->colorStyle = $colorStyle;

        return $this;
    }

    /**
     * Get colorStyle
     *
     * @return string
     */
    public function getColorStyle()
    {
        return $this->colorStyle;
    }

    /**
     * Set logoURL
     *
     * @param string $logoURL
     *
     * @return Entreprise
     */
    public function setLogoURL($logoURL)
    {
        $this->logoURL = $logoURL;

        return $this;
    }

    /**
     * Get logoURL
     *
     * @return string
     */
    public function getLogoURL()
    {
        return $this->logoURL;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Entreprise
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
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add activity
     *
     * @param \ApiBundle\Entity\Activity $activity
     *
     * @return Entreprise
     */
    public function addActivity(\ApiBundle\Entity\Activity $activity)
    {
        $this->activities[] = $activity;

        return $this;
    }

    /**
     * Remove activity
     *
     * @param \ApiBundle\Entity\Activity $activity
     */
    public function removeActivity(\ApiBundle\Entity\Activity $activity)
    {
        $this->activities->removeElement($activity);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivities()
    {
        return $this->activities;
    }
}
