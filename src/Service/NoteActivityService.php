<?php


namespace CelebrityAgent\Service;


use CelebrityAgent\Entity\Activity\Activity;
use CelebrityAgent\Entity\Activity\NoteActivity;
use CelebrityAgent\Entity\Property;
use CelebrityAgent\Form\DTO\ActivityDTO;
use CelebrityAgent\Form\Type\NoteActivityType;


class NoteActivityService extends ActivityService
{
    public function processDTO(ActivityDTO $activityDTO, Property $property, ?Activity $activity = null): Activity
    {
        if (!$activity instanceof Activity) {
            $activity = new NoteActivity($property, $activityDTO->text);
            $this->entityManager->persist($activity);
        } else {
            // only update the text if it changed (and was thus valid)
            if (!empty($activityDTO->text)) {
                $activity->updateRequiredDetails($activityDTO->text);
            }
        }
        // todo add event listener gor owner interface and  inject user automatically
        $activity->setOwner($this->applicationService->getCurrentUser());
        $this->entityManager->flush();

        return $activity;
    }

    protected function getFormForActivity()
    {
        return NoteActivityType::class;
    }
}