<?php


namespace CelebrityAgent\Exception;


use CelebrityAgent\Entity\Activity\Activity;

class ActivityException extends CelebrityAgentException
{
    /**
     * @var int
     */
    const NOT_EMPTY_FOR_DELETION = 300;

    /**
     * Create an exception indicating that a activity is not empty and therefore
     * cannot be removed.
     *
     * @param Activity $activity
     * @return ActivityException
     */
    public static function notForRemoval(Activity $activity): ActivityException
    {
        return new self(sprintf(
            'The activity # "%s of %s" is not empty and thus cannot be removed.',
            $activity->getId(),
            $activity->getProperty()->getName()
        ), self::NOT_EMPTY_FOR_DELETION);
    }
}