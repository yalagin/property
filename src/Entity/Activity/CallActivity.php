<?php


namespace CelebrityAgent\Entity\Activity;


use CelebrityAgent\Entity\Property;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class CallActivity extends Activity
{

    /**
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid number."
     * )
     * @Assert\NotNull(message="You must enter receiver of sms.")
     *
     * telephone number
     * @ORM\Column(type="string")
     */
    protected $sender;
    /**
     * telephone number
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid number."
     * )
     * @Assert\NotNull(message="You must enter receiver of sms.")
     *
     * @ORM\Column(type="string")
     */
    protected $receiver;

    // todo implement media file upload
    protected $media;

    function __construct(Property $property, string $text, string $sender, string $receiver)
    {
        parent::__construct($property, $text);
        $this->sender = $sender;
        $this->receiver = $receiver;
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