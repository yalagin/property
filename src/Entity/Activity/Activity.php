<?php

namespace CelebrityAgent\Entity\Activity;

use CelebrityAgent\Entity\AbstractEntity;
use CelebrityAgent\Entity\Interfaeces\HasOwnerInterface;
use CelebrityAgent\Entity\Property;
use CelebrityAgent\Entity\User;
use CelebrityAgent\Exception\ActivityException;
use CelebrityAgent\Exception\UserException;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "call" = "CallActivity",
 *     "email" = "EmailActivity",
 *     "note" = "NoteActivity",
 *     "sms" = "SmsActivity",
 *     "status" = "StatusChangeActivity",
 *     "activity" = "Activity"
 * })
 */
class Activity extends AbstractEntity implements HasOwnerInterface
{
    public function __construct(Property $property, string $text)
    {
        $this->created = new DateTime();
        $this->property = $property;
        $this->text = $text;
    }

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CelebrityAgent\Entity\User")
     */
    protected $from;

    /**
     * @ORM\ManyToOne(targetEntity="CelebrityAgent\Entity\User")
     */
    protected $to;

    /**
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @ORM\ManyToOne(targetEntity="CelebrityAgent\Entity\Property", inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $property;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    protected $created;

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
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }


    public function getOwner(): ?User
    {
        return $this->from;
    }

    /**
     * Determine if this activity can be removed (to be implemented).
     */
    public function isRemovable(): bool
    {
        return true;
    }

    /**
     * Update a user's required details.
     * @param string $text
     */
    public function updateRequiredDetails(string $text): void
    {
        $this->text = $text;

        $this->validateNonEmptyProperties(ActivityException::class, ['property', 'text']);
    }

    public function setOwner(User $user)
    {
        $this->from = $user;
    }
}