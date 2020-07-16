<?php


namespace CelebrityAgent\Form\DTO;


use Symfony\Component\Validator\Constraints as Assert;

abstract class ActivityDTO
{

    /**
     */
    public $from;

    /**
     */
    public $to;

    /**
     * @Assert\NotNull(message="You must enter some text.")
     */
    public $text;

    /**
     */
    public $property;

    /**
     *
     */
    public $created;


    /**
     * those two for DTO
     * @var bool
     */
    protected $empty;

    /**
     * those two for DTO
     * @var bool
     */
    protected $persisted;

    /**
     * Constructor.
     *
     * @param bool $empty If true, the user's current state is empty.
     * @param bool $persisted If true, the user has been persisted to the database.
     */
    public function __construct(bool $empty = true, bool $persisted = false)
    {
        $this->empty = $empty;
        $this->persisted = $persisted;
    }

    /**
     * Check to see if the underlying User is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->empty;
    }

    /**
     * Check to see if the underlying User is persisted.
     *
     * @return bool
     */
    public function isPersisted(): bool
    {
        return $this->persisted;
    }
}