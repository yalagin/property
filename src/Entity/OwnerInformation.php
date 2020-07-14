<?php


namespace CelebrityAgent\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="owner_information")
 */
class OwnerInformation extends AbstractEntity
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="CelebrityAgent\Entity\Property")
     * @ORM\JoinColumn(nullable=true)
     */
    private $property;

    /**
     * @ORM\ManyToOne(targetEntity="CelebrityAgent\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $belongsTo;

    /**
     * @ORM\Column(name="phone_number", type="string", length=255, nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;
}