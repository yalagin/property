<?php


namespace CelebrityAgent\Service;


use CelebrityAgent\Entity\Activity\Activity;
use CelebrityAgent\Entity\Activity\EmailActivity;
use CelebrityAgent\Entity\Activity\NoteActivity;
use CelebrityAgent\Entity\Property;
use CelebrityAgent\Exception\ActivityException;
use CelebrityAgent\Form\DTO\ActivityDTO;
use CelebrityAgent\Form\DTO\EmailActivityDTO;
use CelebrityAgent\Form\Type\EmailActivityType;
use Symfony\Component\HttpFoundation\Response;
class EmailActivityService extends ActivityService
{

    public function processDTO(ActivityDTO $activityDTO, Property $property, ?Activity $activity = null): Activity
    {
        if (!$activity instanceof Activity) {
            /** @var EmailActivityDTO $activityDTO */
            $activity = new EmailActivity(
                $property, $activityDTO->text, $activityDTO->subject, $activityDTO->sender, $activityDTO->receiver
            );
            $this->entityManager->persist($activity);
        } else {
            throw new ActivityException('You can not update Email it\'s already sent. You can only delete it.',Response::HTTP_FORBIDDEN);
        }
        // todo add event listener gor owner interface and  inject user automatically
        $activity->setOwner($this->applicationService->getCurrentUser());
        $this->entityManager->flush();

        return $activity;
    }

    protected function getFormForActivity()
    {
        return EmailActivityType::class;
    }
}