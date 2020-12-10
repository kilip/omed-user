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

use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Util\EncoderFactoryInterface;
use Omed\User\Contracts\Util\EncoderInterface;
use Omed\User\Mezzio\Util\BcryptEncoder;

class EncoderFactory implements EncoderFactoryInterface
{
    public function getEncoder(UserInterface $user): EncoderInterface
    {
        return new BcryptEncoder();
    }
}
