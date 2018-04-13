<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActivityUser
 *
 * @ORM\Table(name="activity_user")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ActivityUserRepository")
 */
class ActivityUser
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
     * @var bool
     *
     * @ORM\Column(name="editAuth", type="boolean")
     */
    private $editAuth;

    /**
     * @var bool
     *
     * @ORM\Column(name="createAuth", type="boolean")
     */
    private $createAuth;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleteAuth", type="boolean")
     */
    private $deleteAuth;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_submit", type="date")
     */
    private $dateSubmit;

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
     * Set editAuth
     *
     * @param boolean $editAuth
     *
     * @return ActivityUser
     */
    public function setEditAuth($editAuth)
    {
        $this->editAuth = $editAuth;

        return $this;
    }

    /**
     * Get editAuth
     *
     * @return bool
     */
    public function getEditAuth()
    {
        return $this->editAuth;
    }

    /**
     * Set createAuth
     *
     * @param boolean $createAuth
     *
     * @return ActivityUser
     */
    public function setCreateAuth($createAuth)
    {
        $this->createAuth = $createAuth;

        return $this;
    }

    /**
     * Get createAuth
     *
     * @return bool
     */
    public function getCreateAuth()
    {
        return $this->createAuth;
    }

    /**
     * Set deleteAuth
     *
     * @param boolean $deleteAuth
     *
     * @return ActivityUser
     */
    public function setDeleteAuth($deleteAuth)
    {
        $this->deleteAuth = $deleteAuth;

        return $this;
    }

    /**
     * Get deleteAuth
     *
     * @return bool
     */
    public function getDeleteAuth()
    {
        return $this->deleteAuth;
    }

    /**
     * Set dateSubmit
     *
     * @param \DateTime $dateSubmit
     *
     * @return ActivityUser
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
     * Set status
     *
     * @param boolean $status
     *
     * @return ActivityUser
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

