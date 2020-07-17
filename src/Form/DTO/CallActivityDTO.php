<?php


namespace CelebrityAgent\Form\DTO;


use CelebrityAgent\Entity\Activity\CallActivity;
use Symfony\Component\Validator\Constraints as Assert;

class CallActivityDTO extends ActivityDTO
{
    /**
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid number."
     * )
     * @Assert\NotNull(message="You must enter who send that call.")
     */
    public $sender;
    /**
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid number."
     * )
     * @Assert\NotNull(message="You must enter receiver of call.")
     */
    public $receiver;

    /**
     * Create a new NoteActivityDTO from a NoteActivity.
     *
     * @param CallActivity $callActivity
     *
     * @return self
     */
    public static function createFromCallActivity(CallActivity $callActivity): self
    {
        $callActivityDTO = new self($callActivity->isRemovable(), $callActivity->getId() > 0);

        $callActivityDTO->from = $callActivity->getFrom();
        $callActivityDTO->to = $callActivity->getTo();
        $callActivityDTO->text = $callActivity->getText();
        $callActivityDTO->property = $callActivity->getProperty();

        $callActivityDTO->sender = $callActivity->getSender();
        $callActivityDTO->receiver = $callActivity->getReceiver();

        return $callActivityDTO;
    }
}