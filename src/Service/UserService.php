<?php

namespace CelebrityAgent\Service;

use CelebrityAgent\Entity\User;
use CelebrityAgent\Exception\UserException;
use CelebrityAgent\Form\DTO\UserDTO;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class to work with User objects.
 */
class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * Constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * Delete a user while acquiring a row lock to block any incoming
     * modifications or references.
     *
     * @throws UserException
     */
    public function deleteUser(User $user): void
    {
        $this->entityManager->transactional(function ($entityManager) use ($user) {
            $user = $entityManager->find(User::class, $user->getId(), LockMode::PESSIMISTIC_WRITE);

            if (!$user->isEmpty()) {
                throw UserException::notEmptyForRemoval($user);
            }

            $this->entityManager->remove($user);
        });
    }

    /**
     * Process a User DTO.
     */
    public function processDTO(UserDTO $userDto, ?User $user = null): User
    {
        if (!$user instanceof User) {

            $user = new User(
                $userDto->email
            );

            $this->entityManager->persist($user);

        } else {

            // only update the email if it changed (and was thus valid)
            if (!empty($userDto->email)) {

                $user->updateRequiredDetails(
                    $userDto->email
                );

            }

        }

        // if there is a non-empty password, encode and set it
        if (!empty($userDto->password)) {
            $user->encodeAndSetPassword($this->userPasswordEncoder, $userDto->password);
        }

        $user->replaceRoles([$userDto->role]);

        $user->updateNames(
            $userDto->firstName,
            $userDto->lastName
        );

        $this->entityManager->flush();

        return $user;
    }
}
