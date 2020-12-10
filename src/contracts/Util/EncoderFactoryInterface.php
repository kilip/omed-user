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

namespace Omed\User\Contracts\Util;

use Omed\User\Contracts\Model\UserInterface;

interface EncoderFactoryInterface
{
    public function getEncoder(UserInterface $user): EncoderInterface;
}
