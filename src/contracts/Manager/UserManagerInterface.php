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

namespace Omed\User\Contracts\Manager;

use Omed\User\Contracts\Model\UserInterface;

interface UserManagerInterface
{
    public function createUser(): UserInterface;

    public function deleteUser(UserInterface $user): void;

    /**
     * @param array $criteria
     *
     * @return object|UserInterface|null
     */
    public function findBy(array $criteria);

    public function findByUsername(string $username): ?UserInterface;

    public function findByEmail(string $email): ?UserInterface;

    public function findByUsernameOrEmail(string $usernameOrEmail): ?UserInterface;

    public function save(UserInterface $user, bool $andFlush = true): void;

    public function updateCanonicalFields(UserInterface $user): void;

    public function updatePassword(UserInterface $user): void;
}
