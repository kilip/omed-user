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

namespace Omed\User\Mezzio\Util;

use Laminas\Crypt\Password\Bcrypt;
use Omed\User\Contracts\Util\EncoderInterface;

class BcryptEncoder implements EncoderInterface
{
    public function encodePassword(string $plainPassword, ?string $salt): string
    {
        $bcrypt = new Bcrypt();
        return $bcrypt->create($plainPassword);
    }
}
