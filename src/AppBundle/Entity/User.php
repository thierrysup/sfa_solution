<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth; 
use JMS\Serializer\Annotation\Exclude; 
use JMS\Serializer\Annotation as Serializer;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class User extends BaseUser
{

    /**
      *@ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     * @Serializer\Type("int")
     */
    protected $id;
    /**
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $username;

    /**
     * @var string The email of the user.
     *
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $usernameCanonical;

    /**
     * @var string The email of the user.
     *
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $email;


    /**
     * @var string The email of the user.
     *
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $emailCanonical;

    /**
     * @var bool
     * @Serializer\Expose
     * @Serializer\Type("boolean")
     */
    protected $enabled;



    /**
     * @var bool
     * @Serializer\Expose
     * @Serializer\SerializedName("isChange")
     * @Serializer\Type("boolean")
     */
    protected $isChange;

    

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $password;

    

    /**
     * @var \DateTime|null
     * @Serializer\Expose
     * @Serializer\Type("DateTime")
     */
    protected $lastLogin;

    

    /**
     * @var array
     * @Serializer\Expose
     * @Type("array")
     */
    protected $roles;


     /**
     * @var GroupInterface[]|Collection
     * @Serializer\Expose
     * @Type("array")
     */
    protected $groups;


    
    /**
     * @var int
     *
     * 1 client
     * 2 employ
     * 3 admin
     * @ORM\Column(name="type_user", type="integer",nullable=true)
     * @Serializer\Expose
     * @Serializer\Type("int")
     */
    protected $type_user;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string",nullable=true)
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string",nullable=true)
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string",nullable=true)
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string",nullable=true)
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $address;



    /**
     * @var ActivityUser[] Available ActivityUsers for this user.
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\ActivityUser", mappedBy="user",cascade={"remove"}, orphanRemoval=true)
     * @Type("ArrayCollection<ApiBundle\Entity\ActivityUser>")
     * @MaxDepth(1)
     */
    protected $activityUsers;

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
     * Set typeUser
     *
     * @param integer $typeUser
     *
     * @return User
     */
    public function setTypeUser($typeUser)
    {
        $this->type_user = $typeUser;

        return $this;
    }
 
    

    /**
     * Get typeUser
     *
     * @return integer
     */
    public function getTypeUser()
    {
        return $this->type_user;
    }
 
    /**
     * Add activityUser
     *
     * @param \ApiBundle\Entity\ActivityUser $activityUser
     *
     * @return User
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

    public function getRegionId($idAct){

        $idRegion=0;
        foreach ($this->getActivityUsers() as $activityUser) {
            if ($activityUser->getActivity()->getId() === $idAct) {

                switch ($activityUser->getZoneInfluence()) {
                    case 1:
                        $idRegion = $activityUser->getPOS()->getQuarter()->getSector()->getTown()->getRegion()->getId();
                        break;
                    case 2:
                        $idRegion = $activityUser->getQuarter()->getSector()->getTown()->getRegion()->getId();
                        break;
                    case 3:
                        $idRegion = $activityUser->getSector()->getTown()->getRegion()->getId();
                        break;
                    case 4:
                        $idRegion = $activityUser->getTown()->getRegion()->getId();
                        break;
                    case 5:
                        $idRegion = $activityUser->getRegion()->getId();
                        break;
                    default:
                        $idRegion = -1;                }

            }
        }
        return $idRegion;
    }

    public function getZoneInfluence($idAct){
        foreach ($this->getActivityUsers() as $activityUser) {
            if ($activityUser->getActivity()->getId() === $idAct) {
                return $activityUser->getZoneInfluence();
            }
        }
        return 0;

    }

    public function getListOfIdReferenceAreaByActivityId($idAct){

        $idAreas =[];
        foreach ($this->getActivityUsers() as $activityUser) {
            if (($activityUser->getActivity()->getId() === $idAct)) {

                switch ($activityUser->getZoneInfluence()) {
                    case 3:
                        $quarters = $activityUser->getSector()->getQuarters();
                        foreach ($quarters as $quarter) {
                            $idAreas[] = $quarter->getId();
                        }
                        break;
                    case 4:
                        $sectors = $activityUser->getTown()->getSectors();
                        foreach ($sectors as $sector) {
                            $idAreas[] = $sector->getId();
                        }
                        break;
                    default:  {
                        $regions = $activityUser->getCountry()->getRegions();
                        foreach ($regions as $region) {
                            $idAreas[] =$region->getId();
                        }
                    }
                }
                return $idAreas;
            }
        }
        return $idAreas;
    }


    /**
     * Get the value of phone
     *
     * @return  string
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @param  string  $phone
     *
     * @return  self
     */ 
    public function setPhone(string $phone)
    {
        $this->phone = $phone;

        return $this;
    }


     /**
     * Get the value of isChange
     *
     * @return  bool
     */ 
    public function getIsChange()
    {
        return $this->isChange;
    }
    /**
     * Set the value of isChange
     *
     * @param  bool  $isChange
     *
     * @return  self
     */ 
    public function setIsChange(bool $isChange)
    {
        $this->isChange = $isChange;
        return $this;
    }

    /**
     * Get the value of firstname
     *
     * @return  string
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @param  string  $firstname
     *
     * @return  self
     */ 
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     *
     * @return  string
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @param  string  $lastname
     *
     * @return  self
     */ 
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of address
     *
     * @return  string
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @param  string  $address
     *
     * @return  self
     */ 
    public function setAddress(string $address)
    {
        $this->address = $address;

        return $this;
    }
}