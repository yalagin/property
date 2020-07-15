<?php


namespace CelebrityAgent\Controller\BackOffice;

use CelebrityAgent\Entity\Activity\NoteActivity;
use CelebrityAgent\Entity\Property;
use CelebrityAgent\Form\DTO\NoteActivityDTO;
use CelebrityAgent\Form\Type\NoteActivityType;
use CelebrityAgent\Service\ActivityService;
use CelebrityAgent\Service\ApplicationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 */
class ActivityController extends AbstractController
{
    /**
     * @Route("/property/{property_id}/activity/note/manage/{id?}", name="backoffice_note_manage")
     * @Route("/property/{property_id}/activity/note/add", name="backoffice_note_add")
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     * @Entity("property", expr="repository.find(property_id)")
     * @ParamConverter("id", class="CelebrityAgent\Entity\Activity\NoteActivity")
     * @ParamConverter("property_id", class="CelebrityAgent\Entity\Property")
     * @Template
     * @param ApplicationService $applicationService
     * @param Request $request
     * @param ActivityService $activityService
     * @param Property $property
     * @param NoteActivity|null $noteActivity
     * @return array|RedirectResponse
     */
    public function manageNoteAction(ApplicationService $applicationService, Request $request,
                                     ActivityService $activityService, Property $property,
                                     NoteActivity $noteActivity = null)
    {
        //todo later on add validation on user ownership and admin
        if (!is_null($request->attributes->get('id')) && !$noteActivity instanceof NoteActivity) {
            throw $this->createNotFoundException();
        }
        // really strange $includeDelete that I don't understand =) really want to remove it
        $includeDelete =
            $request->isMethod('POST') &&
            $request->request->has('noteActivity') &&
            is_array($request->request->get('noteActivity')) &&
            isset($request->request->get('noteActivity')['delete']);

        // create a DTO for the note, or if there is no note, create an empty DTO
        $noteActivityDTO = $noteActivity instanceof NoteActivity ?
            NoteActivityDTO::createFromNoteActivity($noteActivity) :
            new NoteActivityDTO();
        $noteActivityForm = $this->createForm(NoteActivityType::class, $noteActivityDTO, [
            // admin and owner can delete it
            'include_delete' => $includeDelete || (
                    $noteActivityDTO->isEmpty()
                    && $applicationService->isUserOwnEntity($noteActivity) || $this->isGranted('ROLE_SYSTEM_ADMIN')
                ),
            //todo setting up proper validation
            'validation_groups' => $noteActivity instanceof NoteActivity ? ['manage'] : ['Default']
        ]);

        $noteActivityForm->handleRequest($request);

        if ($noteActivityForm->isSubmitted()) {

            // only system admins or owner can change or delete
            if ((!($noteActivity instanceof NoteActivity) || $this->isGranted('ROLE_SYSTEM_ADMIN'))
                && (!$noteActivity || $applicationService->isUserOwnEntity($noteActivity))) {
                throw $this->createAccessDeniedException();
            }


            $original = $noteActivity instanceof NoteActivity ? '#' . $noteActivity->getId() . ' of ' . $noteActivity->getProperty()->getName() : null;

            if ($noteActivityForm->has('delete') && $noteActivityForm->get('delete')->isClicked()) {
                try {
                    $activityService->deleteActivity($noteActivity);
                    $this->addFlash('notice', sprintf('Activity %s has been removed.', $original));
                    return $this->redirectToRoute('backoffice_property', ['id' => $property->getId()]);
                } catch (\Exception $exception) {
                    $this->addFlash('error', sprintf('Activity cannot be removed.'));
                    return $this->redirectToRoute('backoffice_property', ['id' => $property->getId()]);
                }
            }
            if ($noteActivityForm->isValid()) {
                $noteActivity = $activityService->processDTO($noteActivityDTO, $property, $this->getUser(), $noteActivity);
                $this->addFlash('notice', sprintf('Activty # %s of %s %s.', $noteActivity->getId(), $property->getName(), $original ? 'updated' : 'added'));
                return $this->redirectToRoute('backoffice_property', ['id' => $property->getId()]);
            }
        }


        return [
            'form' => $noteActivityForm->createView(),
            'noteActivity' => $noteActivity,
            'property' => $property,
        ];
    }

    /**
     * @Route("/property/{property_id}/activity/note/add", name="backoffice_note_add")
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     * @Template
     * @param ApplicationService $applicationService
     * @param Request $request
     * @param ActivityService $activityService
     * @param Property $property
     * @param NoteActivity|null $noteActivity
     * @return array|RedirectResponse
     */
    public function createNoteActivity(ApplicationService $applicationService, Request $request,
                                       ActivityService $activityService, Property $property)
    {
        //todo later on add validation on user ownership and admin ?



        // create a DTO for the note, or if there is no note, create an empty DTO
        $noteActivityDTO = new NoteActivityDTO();
        $noteActivityForm = $this->createForm(NoteActivityType::class, $noteActivityDTO, [
            //todo double check it
            'include_delete' => $includeDelete || (!$noteActivityDTO->isEmpty() && ($noteActivity->getOwner() === $this->getUser())),
            //todo setting up proper validation
            'validation_groups' => $noteActivity instanceof NoteActivity ? ['manage'] : ['Default']
        ]);

        $noteActivityForm->handleRequest($request);

        if ($noteActivityForm->isSubmitted()) {

            // only system admins or owner can change or delete
            if ($noteActivity instanceof NoteActivity && !$this->isGranted('ROLE_SYSTEM_ADMIN')
                ||$noteActivity && !$applicationService->isUserOwnEntity($noteActivity)) {
                throw $this->createAccessDeniedException();
            }

            $original = $noteActivity instanceof NoteActivity ? '#' . $noteActivity->getId() . ' of ' . $noteActivity->getProperty()->getName() : null;

            if ($noteActivityForm->has('delete') && $noteActivityForm->get('delete')->isClicked()) {
                try {
                    $activityService->deleteActivity($noteActivity);
                    $this->addFlash('notice', sprintf('Activity %s has been removed.', $original));
                    return $this->redirectToRoute('backoffice_property', ['id' => $property->getId()]);
                } catch (\Exception $exception) {
                    $this->addFlash('error', sprintf('Activity cannot be removed.'));
                    return $this->redirectToRoute('backoffice_property', ['id' => $property->getId()]);
                }
            }
            if ($noteActivityForm->isValid()) {
                $noteActivity = $activityService->processDTO($noteActivityDTO, $property,$this->getUser(), $noteActivity);
                $this->addFlash('notice', sprintf('Activty # %s of %s %s.', $noteActivity->getId(), $property->getName(), $original ? 'updated' : 'added'));
                return $this->redirectToRoute('backoffice_property', ['id' => $property->getId()]);
            }
        }


        return [
            'form' => $noteActivityForm->createView(),
            'noteActivity' => $noteActivity,
            'property' => $property,
        ];
    }
}