<?php


namespace CelebrityAgent\Entity;

use CelebrityAgent\Exception\UserException;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="CelebrityAgent\Repository\PropertyRepository")
 * @ORM\Table(name="property")
 */
class Property extends AbstractEntity
{

    public function __construct() {
        $this->activities = new ArrayCollection();
    }
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * actually from my experience is the best to store in the string format in cents and than convert it to int with decimal
     * in that case we can support bitcoin currency. but I will go safe https://rietta.com/blog/best-data-types-for-currencymoney-in/
     * @ORM\Column(name="commission_amount", type="decimal", precision=13, scale=2, nullable=true)
     */
    private $commissionAmount;

    /**
     * @ORM\Column(name="price", type="decimal", precision=13, scale=2, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(name="picture_url", type="string", length=255, nullable=true)
     */
    private $pictureUrl;

    /**
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string")
     */
    private $mailingAddress;

    /**
     * One property has many activities. This is the inverse side.
     * @ORM\OneToMany(targetEntity="CelebrityAgent\Entity\Activity\Activity", mappedBy="property")
     */
    private $activities;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCommissionAmount()
    {
        return $this->commissionAmount;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getMailingAddress()
    {
        return $this->mailingAddress;
    }

    /**
     * @return ArrayCollection
     */
    public function getActivities()
    {
        return $this->activities;
    }
}