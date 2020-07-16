<?php


namespace CelebrityAgent\Entity\Activity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class StatusChangeActivity extends Activity
{
    /**
     * maybe we should use sender and receiver to save database space?
     * @ORM\Column(type="string")
     */
    protected $initial;
    /**
     * @ORM\Column(type="string")
     */
    Protected $final;

    /**
     * @return mixed
     */
    public function getInitial()
    {
        return $this->initial;
    }

    /**
     * @return mixed
     */
    public function getFinal()
    {
        return $this->final;
    }
}