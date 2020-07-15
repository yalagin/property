<?php


namespace CelebrityAgent\Form\DTO;

use CelebrityAgent\Entity\Activity\NoteActivity;
use CelebrityAgent\Entity\User;

/**
 * Facilitate transfer of data between a form and a NoteActivity.
 */
class NoteActivityDTO extends ActivityDTO
{

    /**
     * Create a new NoteActivityDTO from a NoteActivity.
     *
     * @param NoteActivity $noteActivity
     *
     * @return NoteActivityDTO
     */
    public static function createFromNoteActivity(NoteActivity $noteActivity): NoteActivityDTO
    {
        $noteActivityDTO = new self($noteActivity->isRemovable(), $noteActivity->getId() > 0);

        $noteActivityDTO->from = $noteActivity->getFrom();
        $noteActivityDTO->to = $noteActivity->getTo();
        $noteActivityDTO->text = $noteActivity->getText();
        $noteActivityDTO->property = $noteActivity->getProperty();

        return $noteActivityDTO;
    }
}