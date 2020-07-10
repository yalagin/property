<?php

namespace CelebrityAgent\Form\DTO;

use CelebrityAgent\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Facilitate transfer of data between a form and a User.
 */
class UserDTO
{
    /**
     * @var string
     *
     * @Assert\Email(groups={"manage"}, message="Please enter a valid email address.")
     * @Assert\NotNull(groups={"manage"}, message="You must enter an email address.")
     */
    public $email;

    /**
     * @var string
     *
     * @Assert\NotNull(groups={"manage"}, message="You must enter a first name.")
     */
    public $firstName;

    /**
     * @var string
     *
     * @Assert\NotNull(groups={"manage"}, message="You must enter a last name.")
     */
    public $lastName;

    /**
     * @var string
     *
     * @Assert\NotNull(message="You must enter a password.")
     */
    public $password;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"manage"}, message="You must select a valid role.")
     */
    public $role;

    /**
     * @var bool
     */
    private $empty;

    /**
     * @var bool
     */
    private $persisted;

    /**
     * Constructor.
     *
     * @param bool $empty If true, the user's current state is empty.
     * @param bool $persisted If true, the user has been persisted to the database.
     */
    public function __construct(bool $empty = true, bool $persisted = false)
    {
        $this->empty = $empty;
        $this->persisted = $persisted;

        $this->role = 'ROLE_USER';

        $this->password = null;
    }

    /**
     * Create a new UserDTO from a User.
     *
     * @param User $user
     *
     * @return UserDTO
     */
    public static function createFromUser(User $user): UserDTO
    {
        $userDto = new self($user->isEmpty(), $user->getId() > 0);

        $userDto->email = $user->getEmail();
        $userDto->firstName = $user->getFirstName();
        $userDto->lastName = $user->getLastName();

        $userDto->role = $user->getRoles()[0];

        return $userDto;
    }

    /**
     * Check to see if the underlying User is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->empty;
    }

    /**
     * Check to see if the underlying User is persisted.
     *
     * @return bool
     */
    public function isPersisted(): bool
    {
        return $this->persisted;
    }
}
