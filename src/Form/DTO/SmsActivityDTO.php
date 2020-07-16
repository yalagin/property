<?php


namespace CelebrityAgent\Form\DTO;


use CelebrityAgent\Entity\Activity\SmsActivity;
use Symfony\Component\Validator\Constraints as Assert;

class SmsActivityDTO extends ActivityDTO
{
    /**
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\NotNull(message="You must enter who send that sms.")
     */
    public $sender;
    /**
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\NotNull(message="You must enter receiver of sms.")
     */
    public $receiver;

    /**
     * Create a new NoteActivityDTO from a NoteActivity.
     *
     * @param SmsActivity $smsActivity
     *
     * @return self
     */
    public static function createFromSmsActivity(SmsActivity $smsActivity): self
    {
        $smsActivityDTO = new self($smsActivity->isRemovable(), $smsActivity->getId() > 0);

        $smsActivityDTO->from = $smsActivity->getFrom();
        $smsActivityDTO->to = $smsActivity->getTo();
        $smsActivityDTO->text = $smsActivity->getText();
        $smsActivityDTO->property = $smsActivity->getProperty();

        $smsActivityDTO->sender = $smsActivity->getSender();
        $smsActivityDTO->receiver = $smsActivity->getReceiver();

        return $smsActivityDTO;
    }
}