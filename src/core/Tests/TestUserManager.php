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

namespace Omed\User\Core\Tests;

use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Util\CanonicalFieldsUpdaterInterface;
use Omed\User\Contracts\Util\PasswordUpdaterInterface;
use Omed\User\Core\Manager\UserManager;

final class TestUserManager extends UserManager
{
    /**
     * @var TestRepository
     */
    private TestRepository $testObjectManager;

    public function __construct(
        TestRepository $testObjectManager,
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalUpdater,
        string $userClass
    ) {
        parent::__construct($passwordUpdater, $canonicalUpdater, $userClass);
        $this->testObjectManager = $testObjectManager;
    }

    public function findBy(array $criteria)
    {
        return $this->testObjectManager->findBy($criteria);
    }

    public function deleteUser(UserInterface $user): void
    {
    }

    public function save(UserInterface $user, bool $andFlush = true): void
    {
    }
}
