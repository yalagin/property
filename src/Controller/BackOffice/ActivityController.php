<?php


namespace CelebrityAgent\Controller\BackOffice;

use CelebrityAgent\Entity\Activity\NoteActivity;
use CelebrityAgent\Entity\Property;
use CelebrityAgent\Form\DTO\NoteActivityDTO;
use CelebrityAgent\Service\ActivityService;
use CelebrityAgent\Service\EmailActivityService;
use CelebrityAgent\Service\NoteActivityService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  * @IsGranted("ROLE_USER")
 */
class ActivityController extends AbstractController
{
    /**
     * @Route("/property/{id}/activity/note/add", name="backoffice_note_add")
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     * @param NoteActivityService $activityService
     * @param Property $property
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addNoteActivityAction(NoteActivityService $activityService, Property $property)
    {
        $form = $activityService->getActivityForm(new NoteActivityDTO());

        if ($form->isSubmitted() && $form->isValid()) {
            return $activityService->handleFormSubmission($property);
        }

        return $this->render('back_office/activity/manage_note.html.twig', [
            'form' => $form->createView(),
            'property' => $property,
        ]);
    }

    /**
     * @Route("/activity/note/manage/{id}", name="backoffice_note_manage")
     * @param NoteActivityService $activityService
     * @param NoteActivity $noteActivity
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * actually dont need to add checking if $noteActivity is found or not ParamConverter will throw 404 for us and user will
     * not see dubbing msg
     */
    public function editNoteAction(NoteActivityService $activityService, NoteActivity $noteActivity)
    {
        $noteActivityDTO = NoteActivityDTO::createFromNoteActivity($noteActivity);
        $form = $activityService->getActivityForm($noteActivityDTO, $noteActivity);
        $property = $noteActivity->getProperty();

        if ($form->isSubmitted()) {
            $activityService->isUserGrantedPermissionToModify($noteActivity);
            if ($form->has('delete') && $form->get('delete')->isClicked()) {
                return $activityService->handleDeleteButton($property, $noteActivity);
            }
            if ($form->isValid()) {
                return $activityService->handleFormSubmission($property, $noteActivity);
            }
        }

        return $this->render('back_office/activity/manage_note.html.twig', [
            'form' => $form->createView(),
            'noteActivity' => $noteActivity,
            'property' => $property,
        ]);
    }
}