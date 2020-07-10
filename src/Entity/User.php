<?php

namespace CelebrityAgent\Entity;

use CelebrityAgent\Exception\UserException;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="user",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="unique_email", columns={"email"})
 *     }
 * )
 */
class User extends AbstractEntity implements EncoderAwareInterface, EquatableInterface, UserInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(name="roles", type="simple_array", nullable=false)
     */
    private $roles;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(name="last_seen", type="datetime", nullable=true)
     */
    private $lastSeen;

    /**
     * Constructor. A temporary, unknown password will be assigned.
     *
     * @throws UserException
     */
    public function __construct(string $email)
    {
        $this->email = $email;

        $this->created = new DateTime();
        $this->password = uniqid();
        $this->roles = ['ROLE_USER'];

        $this->validateNonEmptyProperties(UserException::class, ['email']);
    }

    /**
     * Display as string using the first and last names, or email
     * if those are empty.
     */
    public function __toString(): string
    {
        if (empty($this->firstName) && empty($this->lastName)) {
            return $this->email;
        }

        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * Add the specified role to the user.
     */
    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    /**
     * Encode and set the user's password.
     */
    public function encodeAndSetPassword(UserPasswordEncoderInterface $userPasswordEncoder, string $password): void
    {
        // by nullifying the salt, we indicate that we no longer use the legacy encoder
        $this->salt = null;

        $this->password = $userPasswordEncoder->encodePassword($this, $password);
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * Get the created date.
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * Get the email address.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getEncoderName()
    {
        return null;
    }

    /**
     * Get the first name.
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Get the database ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the last name.
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Get the last seen date.
     */
    public function getLastSeen(): ?DateTime
    {
        return $this->lastSeen;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Get the valid roles.
     *
     * @return array
     */
    public static function getValidRoles(): array
    {
        return [
            'Agent' => 'ROLE_USER',
            'Administrator' => 'ROLE_ADMIN',
            'System Administrator' => 'ROLE_SYSTEM_ADMIN'
        ];
    }

    /**
     * Determine if this user is empty (to be implemented).
     */
    public function isEmpty(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->email !== $user->getEmail()) {
            return false;
        }

        if ($this->firstName !== $user->getFirstName() || $this->lastName !== $user->getLastName()) {
            return false;
        }

        return true;
    }

    /**
     * Replace the user's roles.
     */
    public function replaceRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Mark a user as seen.
     */
    public function seen(): void
    {
        $this->lastSeen = new DateTime();
    }

    /**
     * Update a user's names.
     */
    public function updateNames(?string $firstName, ?string $lastName): void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Update a user's required details.
     *
     * @param string $email An email address.
     *
     * @throws UserException
     */
    public function updateRequiredDetails(string $email): void
    {
        $this->email = $email;

        $this->validateNonEmptyProperties(UserException::class, ['email']);
    }
}
