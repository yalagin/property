<?php


namespace CelebrityAgent\Service;


use CelebrityAgent\Entity\Activity\Activity;
use CelebrityAgent\Entity\Activity\NoteActivity;
use CelebrityAgent\Entity\Property;
use CelebrityAgent\Entity\User;
use CelebrityAgent\Exception\ActivityException;
use CelebrityAgent\Exception\UserException;
use CelebrityAgent\Form\DTO\ActivityDTO;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ActivityService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Delete a user while acquiring a row lock to block any incoming
     * modifications or references.
     *
     * @param Activity $activity
     */
    public function deleteActivity(Activity $activity): void
    {
        $this->entityManager->transactional(function ($entityManager) use ($activity) {
            $activity = $entityManager->find(Activity::class, $activity->getId(), LockMode::PESSIMISTIC_WRITE);
            if (!$activity->isRemovable()) {
                throw ActivityException::notForRemoval($activity);
            }
            $this->entityManager->remove($activity);
        });
    }

    /**
     * Process an Activity DTO.
     * @param ActivityDTO $activityDTO
     * @param Property $property
     * @param User $loggedInUser
     * @param Activity|null $activity
     * @return Activity
     */
    public function processDTO(ActivityDTO $activityDTO, Property $property,User $loggedInUser, ?Activity $activity = null): Activity
    {
        if (!$activity instanceof Activity) {
            $activity = new NoteActivity($property,$activityDTO->text);
            $this->entityManager->persist($activity);
        } else {
            // only update the text if it changed (and was thus valid)
            if (!empty($activityDTO->text)) {
                $activity->updateRequiredDetails($activityDTO->text);
            }
        }
        // todo add event listener and  inject user automatically
        $activity->setOwner($loggedInUser);
        $this->entityManager->flush();
        return $activity;
    }
}