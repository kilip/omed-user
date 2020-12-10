<?php

declare(strict_types=1);

namespace Omed\User\Contracts\Updater;


use Omed\User\Contracts\Model\UserInterface;

interface PasswordUpdaterInterface
{
    public function hashPassword(UserInterface $user): void;
}