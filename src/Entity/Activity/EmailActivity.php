<?php


namespace CelebrityAgent\Entity\Activity;


use CelebrityAgent\Entity\Property;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class EmailActivity extends Activity
{
    /**
     * @ORM\Column(type="string")
     */
    protected $subject;
    /**
     * @ORM\Column(type="string")
     */
    protected $sender;
    /**
     * @ORM\Column(type="string")
     */
    protected $receiver;

    function __construct(Property $property, string $text, string $subject, string $sender, string $receiver)
    {
        parent::__construct($property, $text);
        $this->subject = $subject;
        $this->sender = $sender;
        $this->receiver = $receiver;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
}