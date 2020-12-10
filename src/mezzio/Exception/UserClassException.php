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

namespace Omed\User\Mezzio\Exception;

use Exception;
use Omed\User\Contracts\Model\UserInterface;

class UserClassException extends Exception
{
    public static function undefined(): self
    {
        return new self('User class is undefined.');
    }

    public static function classNotExists(string $class): self
    {
        return new self("User class {$class} is not exists.");
    }

    public static function incompatible(string $class): self
    {
        return new self("User class {$class} should implement ".UserInterface::class);
    }
}
