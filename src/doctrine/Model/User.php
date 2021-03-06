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

namespace Omed\User\Doctrine\Model;

use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Model\UserTrait;

abstract class User implements UserInterface
{
    use UserTrait;
}
