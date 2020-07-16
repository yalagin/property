<?php

namespace CelebrityAgent\Controller\BackOffice;

use CelebrityAgent\Exception\UserException;
use CelebrityAgent\Form\DTO\UserDTO;
use CelebrityAgent\Form\Type\UserType;
use CelebrityAgent\Entity\User;
use CelebrityAgent\Service\ApplicationService;
use CelebrityAgent\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/browse", name="backoffice_users_browse")
     * template looks cool but it's not the best for everything
     * https://symfony.com/doc/current/best_practices.html#don-t-use-annotations-to-configure-the-controller-template
     * @Template
     */
    public function browseAction(PaginatorInterface $paginator, Request $request)
    {
        $userRepository = $this->getDoctrine()->getManager()->getRepository(User::class);

        $pagination = $paginator->paginate(
            $userRepository->findAll(),
            $request->query->getInt('page', 1),
            20,
            [
                'defaultSortDirection' => 'asc',
                'defaultSortFieldName' => 'lastName'
            ]
        );

        return [
            'pagination' => $pagination,
            'total' => count($userRepository->findAll())
        ];
    }

    /**
     * @Route("/manage/{id?}", name="backoffice_user_manage")
     * @Route("/add", name="backoffice_user_add")
     * @Template
     */
    public function manageAction(ApplicationService $applicationService, Request $request, UserService $userService, User $user = null)
    {
        if (!is_null($request->attributes->get('id')) && !$user instanceof User) {
            throw $this->createNotFoundException();
        }

        $includeDelete =
            $request->isMethod('POST') &&
            $request->request->has('user') &&
            is_array($request->request->get('user')) &&
            isset($request->request->get('user')['delete'])
        ;

        // create a DTO for the user, or if there is no user, create an empty DTO
        $userDto = $user instanceof User ?
            UserDTO::createFromUser($user) :
            new UserDTO()
        ;

        $userForm = $this->createForm(UserType::class, $userDto, [
            'include_delete' => $includeDelete || ($userDto->isEmpty() && ($user != $this->getUser())),
            'include_details' => true,
            'include_password' => true,
            'include_role' => true,
            'repeat_password' => false,
            'validation_groups' => $user instanceof User ? ['manage'] : ['profile', 'Default']
        ]);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted()) {

            // only system admins may make changes to admins or other system admins
            if ($user instanceof User && !$this->isGranted('ROLE_SYSTEM_ADMIN') && $applicationService->userHasRole($user, 'ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }

            $original = $user instanceof User ? $user->getFirstName() . ' ' . $user->getLastName() : null;

            if ($userForm->has('delete') && $userForm->get('delete')->isClicked()) {

                try {

                    $userService->deleteUser($user);

                    $this->addFlash('notice', sprintf('User %s has been removed.', $original));

                    return $this->redirectToRoute('backoffice_users_browse');

                } catch (UserException $userException) {

                    $this->addFlash('error', sprintf('User %s %s is not empty and cannot be removed.', $user->getFirstName(), $user->getLastName()));

                    return $this->redirectToRoute('backoffice_user_manage', ['id' => $user->getId()]);
                }

            }

            if ($userForm->isValid()) {

                $user = $userService->processDTO($userDto, $user);

                $this->addFlash('notice', sprintf('User %s %s %s.', $user->getFirstName(), $user->getLastName(), $original ? 'updated' : 'added'));

                return $this->redirectToRoute('backoffice_users_browse');

            }

        }

        return [
            'form' => $userForm->createView(),
            'user' => $user
        ];
    }
}
