<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entreprise
 *
 * @ORM\Table(name="entreprise")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\EntrepriseRepository")
 */
class Entreprise
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="pobox", type="string", length=255)
     */
    private $pobox;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="colorStyle", type="string", length=255)
     */
    private $colorStyle;

    /**
     * @var string
     *
     * @ORM\Column(name="logoURL", type="string", length=255)
     */
    private $logoURL;

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
}

