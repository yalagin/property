<?php


namespace CelebrityAgent\Service;

use CelebrityAgent\Entity\Activity\Activity;
use CelebrityAgent\Entity\Activity\CallActivity;
use CelebrityAgent\Entity\Property;
use CelebrityAgent\Exception\ActivityException;
use CelebrityAgent\Form\DTO\ActivityDTO;
use CelebrityAgent\Form\DTO\CallActivityDTO;
use CelebrityAgent\Form\Type\CallActivityType;
use Symfony\Component\HttpFoundation\Response;

class CallActivityService extends ActivityService
{

    public function processDTO(ActivityDTO $activityDTO, Property $property, ?Activity $activity = null): Activity
    {
        if (!$activity instanceof Activity) {
            /** @var CallActivityDTO $activityDTO */
            $activity = new CallActivity(
                $property, $activityDTO->text, $activityDTO->sender, $activityDTO->receiver
            );
            $this->entityManager->persist($activity);
        } else {
            throw new ActivityException('You can not update Call it\'s already happened. You can only delete it.',Response::HTTP_FORBIDDEN);
        }
        // todo add event listener gor owner interface and  inject user automatically
        $activity->setOwner($this->applicationService->getCurrentUser());
        $this->entityManager->flush();

        return $activity;
    }

    protected function getFormForActivity()
    {
        return CallActivityType::class;
    }
}