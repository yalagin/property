<?php


namespace CelebrityAgent\Form\DTO;


use CelebrityAgent\Entity\Activity\EmailActivity;
use Symfony\Component\Validator\Constraints as Assert;

class EmailActivityDTO extends ActivityDTO
{

    /**
     * @Assert\NotNull(message="You must enter subject of email.")
     */
    public $subject;
    /**
     * @Assert\NotNull(message="You must enter who send that email.")
     */
    public $sender;
    /**
     * @Assert\NotNull(message="You must enter receiver of email.")
     */
    public $receiver;

    /**
     * Create a new NoteActivityDTO from a NoteActivity.
     *
     * @param EmailActivity $emailActivity
     *
     * @return self
     */
    public static function createFromEmailActivity(EmailActivity $emailActivity): self
    {
        $emailActivityDTO = new self($emailActivity->isRemovable(), $emailActivity->getId() > 0);

        $emailActivityDTO->from = $emailActivity->getFrom();
        $emailActivityDTO->to = $emailActivity->getTo();
        $emailActivityDTO->text = $emailActivity->getText();
        $emailActivityDTO->property = $emailActivity->getProperty();

        $emailActivityDTO->subject =$emailActivity->getSubject();
        $emailActivityDTO->sender = $emailActivity->getSender();
        $emailActivityDTO->receiver = $emailActivity->getReceiver();

        return $emailActivityDTO;
    }
}