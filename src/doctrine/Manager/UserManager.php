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

namespace Omed\User\Doctrine\Manager;

use Doctrine\Persistence\ObjectManager;
use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Updater\CanonicalFieldsUpdaterInterface;
use Omed\User\Contracts\Updater\PasswordUpdaterInterface;
use Omed\User\Core\Manager\UserManager as BaseUserManager;

class UserManager extends BaseUserManager
{
    /**
     * @var ObjectManager
     */
    private ObjectManager $om;

    public function __construct(
        ObjectManager $om,
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        string $userClass
    ) {
        parent::__construct($passwordUpdater, $canonicalFieldsUpdater, $userClass);
        $this->om = $om;
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria)
    {
        return $this->om->getRepository($this->userClass)->findOneBy($criteria);
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
