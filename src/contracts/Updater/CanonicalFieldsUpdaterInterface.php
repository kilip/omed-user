<?php

declare(strict_types=1);

namespace Omed\User\Contracts\Updater;

use Omed\User\Contracts\Model\UserInterface;

interface CanonicalFieldsUpdaterInterface
{
    public function canonicalizeUsername(string $username): string;

    public function canonicalizeMail(string $email): string;

    public function updateCanonicalFields(UserInterface $user);
}