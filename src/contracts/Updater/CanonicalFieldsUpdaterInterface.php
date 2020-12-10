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

namespace Omed\User\Contracts\Updater;

use Omed\User\Contracts\Model\UserInterface;

interface CanonicalFieldsUpdaterInterface
{
    public function canonicalizeUsername(string $username): string;

    public function canonicalizeMail(string $email): string;

    public function updateCanonicalFields(UserInterface $user): void;
}
