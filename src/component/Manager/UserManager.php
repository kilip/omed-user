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

namespace Omed\User\Component\Manager;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Omed\User\Contracts\Manager\UserManagerInterface;
use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Updater\CanonicalFieldsUpdaterInterface;
use Omed\User\Contracts\Updater\PasswordUpdaterInterface;

final class UserManager implements UserManagerInterface
{
    private PasswordUpdaterInterface $passwordUpdater;

    private CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater;

    private string $userClass;

    private ObjectManager $om;

    public function __construct(
        ObjectManager $om,
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        string $userClass
    ) {
        $this->passwordUpdater        = $passwordUpdater;
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
        $this->userClass              = $userClass;
        $this->om                     = $om;
    }

    public function createUser(): UserInterface
    {
        /** @var UserInterface $user */
        return new $this->userClass();
    }

    public function findBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
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

    private function getRepository(): ObjectRepository
    {
        return $this->om->getRepository($this->userClass);
    }

    public function deleteUser(UserInterface $user): void
    {
        $this->om->remove($user);
        $this->om->flush();
    }

    public function save(UserInterface $user, bool $andFlush = true): void
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        $this->om->persist($user);

        if ($andFlush) {
            $this->om->flush();
        }
    }
}
