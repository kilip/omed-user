<?php

/*
 * This file is part of the Omed User project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Omed\User\Core\Manager;

use Omed\User\Contracts\Manager\UserManagerInterface;
use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Util\CanonicalFieldsUpdaterInterface;
use Omed\User\Contracts\Util\PasswordUpdaterInterface;

abstract class UserManager implements UserManagerInterface
{
    protected PasswordUpdaterInterface $passwordUpdater;

    protected CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater;

    protected string $userClass;

    public function __construct(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        string $userClass
    ) {
        $this->passwordUpdater        = $passwordUpdater;
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
        $this->userClass              = $userClass;
    }

    public function createUser(): UserInterface
    {
        return new $this->userClass();
    }

    public function findByUsername(string $username): ?UserInterface
    {
        return $this->findBy(['usernameCanonical' => $this->canonicalFieldsUpdater->canonicalizeUsername($username)]);
    }

    public function findByEmail(string $email): ?UserInterface
    {
        return $this->findBy(['emailCanonical' => $this->canonicalFieldsUpdater->canonicalizeMail($email)]);
    }

    public function findByUsernameOrEmail(string $usernameOrEmail): ?UserInterface
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            $user = $this->findByEmail($usernameOrEmail);
            if (null !== $user) {
                return $user;
            }
        }

        return $this->findByUsername($usernameOrEmail);
    }

    public function updateCanonicalFields(UserInterface $user): void
    {
        $this->canonicalFieldsUpdater->updateCanonicalFields($user);
    }

    public function updatePassword(UserInterface $user): void
    {
        $this->passwordUpdater->hashPassword($user);
    }
}
