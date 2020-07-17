<?php


namespace CelebrityAgent\Entity\Activity;


use CelebrityAgent\Entity\Property;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class StatusChangeActivity extends Activity
{
    /**
     * maybe we should use sender and receiver to save database space?
     * @ORM\Column(type="string")
     * @Assert\NotNull
     */
    protected $initial;
    /**
     * @ORM\Column(type="string")
     *  @Assert\NotNull
     */
    Protected $final;

    function __construct(Property $property, string $text, string $initial, string $final)
    {
        parent::__construct($property, $text);
        $this->initial = $initial;
        $this->final = $final;
    }

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