<?php

namespace CelebrityAgent\Service;

use CelebrityAgent\Entity\Interfaeces\HasOwnerInterface;
use CelebrityAgent\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Service for working with application basics.
 */
class ApplicationService
{
    /**
     * @var \Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface
     */
    private $accessDecisionManager;

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var \Symfony\Component\Security\Core\Security
     */
    private $security;

    /**
     * Constructor.
     */
    public function __construct(AccessDecisionManagerInterface $accessDecisionManager, AuthorizationCheckerInterface $authorizationChecker, Security $security)
    {
        $this->accessDecisionManager = $accessDecisionManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->security = $security;
    }

    /**
     * Get the currently-logged in user, if any.
     *
     * @return UserInterface|null
     */
    public function getCurrentUser(): ?UserInterface
    {
        return $this->security->getUser();
    }

    /**
     * Return true if the logged in user is an administrator.
     *
     * @return bool
     */
    public function isAdministrator(): bool
    {
        return $this->authorizationChecker->isGranted('ROLE_ADMIN');
    }

    /**
     * Return true if the logged in user is a system administrator.
     *
     * @return bool
     */
    public function isSystemAdministrator(): bool
    {
        return $this->authorizationChecker->isGranted('ROLE_SYSTEM_ADMIN');
    }

    /**
     * Determine if the specified user has the given role or not.
     *
     * @param User $user The user whose access to check.
     * @param string $role The role to look for.
     *
     * @return bool
     */
    public function userHasRole(User $user, string $role): bool
    {
        return $this->accessDecisionManager->decide(
            new UsernamePasswordToken($user, 'none', 'none', $user->getRoles()),
            [$role]
        );
    }

    /**
     *  Determine if the specified user owns the given entity or not.
     *
     * @param HasOwnerInterface $hasOwnerEntity
     * @return bool
     */
    public function isUserOwnEntity(HasOwnerInterface $hasOwnerEntity)
    {
        $owners = $hasOwnerEntity->getOwner();
        if($owners instanceof ArrayCollection){
            return in_array($this->getCurrentUser(), $owners->toArray()) ;
        }
        return $this->getCurrentUser() === $owners;
    }

    /**
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceOf($var, $instance) {
        return $var instanceof $instance;
    }
}
