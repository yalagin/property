<?php


namespace CelebrityAgent\Service;


use CelebrityAgent\Entity\Activity\Activity;
use CelebrityAgent\Entity\Property;
use CelebrityAgent\Exception\ActivityException;
use CelebrityAgent\Form\DTO\ActivityDTO;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

abstract class ActivityService
{

    abstract public function processDTO(ActivityDTO $activityDTO, Property $property, ?Activity $activity = null): Activity;

    abstract protected function getFormForActivity();

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var ApplicationService
     */
    protected $applicationService;
    /**
     * @var RouterInterface
     */
    protected $router;
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * Constructor.
     * @param EntityManagerInterface $entityManager
     * @param ApplicationService $applicationService
     * @param RouterInterface $router
     * @param RequestStack $requestStack
     */
    public function __construct(EntityManagerInterface $entityManager,
                                ApplicationService $applicationService,
                                RouterInterface $router,
                                RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->request = $requestStack->getCurrentRequest();
        $this->applicationService = $applicationService;
        $this->router = $router;
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



    // for 404
    public function checkIfThereIsEntity($activity, $class): void
    {
        if (!is_null($this->request->attributes->get('id')) && !$activity instanceof $class) {
            throw new NotFoundHttpException("Not Found");
        }
    }


    /**
     * generalization of method
     * @param string $what
     * @return bool
     */
    public function isIncludingDelete(string $what): bool
    {
        return
            $this->request->isMethod('POST') &&
            $this->request->request->has($what) &&
            is_array($this->request->request->get($what)) &&
            isset($this->request->request->get($what)['delete']);
    }


    /**
     * @param ActivityDTO $activityDTO
     * @param Activity $activity
     * @return FormInterface
     */
    public function getActivityForm(ActivityDTO $activityDTO, ?Activity $activity = null): FormInterface
    {
        $formFactory = Forms::createFormFactory();
        $noteActivityForm = $formFactory->create($this->getFormForActivity(), $activityDTO, [
            'include_delete' => $this->isIncludingDelete('activity')
                || ($activityDTO->isEmpty() && $activity && $this->isUserGrantedPermissionToModifyBool($activity)),
            'validation_groups' => $activity ? ['manage'] : ['Default']
        ]);

        return $this->form = $noteActivityForm->handleRequest();
    }


    /**
     * @param Activity $activity
     * todo later on add validation to php docs
     * only system admins or owner can change or delete
     * @return bool
     */
    public function isUserGrantedPermissionToModifyBool(Activity $activity): bool
    {
        if (($activity && $this->applicationService->isSystemAdministrator())
            || ($activity && $this->applicationService->isUserOwnEntity($activity))) {
            return true;
        }
        return false;
    }

    /**
     * @param Activity $activity
     * todo later on add validation to php docs
     * only system admins or owner can change or delete
     * @return bool
     */
    public function isUserGrantedPermissionToModify(Activity $activity): bool
    {
        if (!$this->isUserGrantedPermissionToModifyBool($activity)) {
            throw new AccessDeniedHttpException();
        }
        return true;
    }

    public function handleFormSubmission(Property $property, ?Activity $activity = null)
    {
        $original = $this->getOriginal($activity);
        $activity = $this->processDTO($this->form->getData(), $property, $activity);

        $this->request->getSession()->getFlashBag()
            ->add('notice', sprintf('Activity # %s of %s %s.', $activity->getId(), $property->getName(), $original ? 'updated' : 'added'));
        return new RedirectResponse($this->router->generate('backoffice_property', ['id' => $property->getId()]));
    }

    public function handleDeleteButton(Property $property, Activity $activity)
    {
        $original = $this->getOriginal($activity);
        try {
            $this->deleteActivity($activity);
            $this->request->getSession()->getFlashBag()->add('notice', sprintf('Activity %s has been removed.', $original));
            return new RedirectResponse($this->router->generate('backoffice_property', ['id' => $property->getId()]));
        } catch (\Exception $exception) {
            $this->request->getSession()->getFlashBag()->add('error', sprintf('Activity cannot be removed.'));
            return new RedirectResponse($this->router->generate('backoffice_property', ['id' => $property->getId()]));
        }
    }

    private function getOriginal(?Activity $activity = null)
    {
        if(!$activity){
            return false;
        }
        return '#' . $activity->getId() . ' of ' . $activity->getProperty()->getName();
    }
}