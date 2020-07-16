<?php


namespace CelebrityAgent\Entity\Activity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class SmsActivity extends Activity
{


    /**
     * telephone number ?
     * @ORM\Column(type="string")
     */
    protected $sender;
    /**
     * telephone number ?
     * @ORM\Column(type="string")
     */
    protected $receiver;



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