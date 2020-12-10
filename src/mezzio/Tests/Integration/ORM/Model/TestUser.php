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

namespace Omed\User\Mezzio\Tests\Integration\ORM\Model;

use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Model\UserTrait;

class TestUser implements UserInterface
{
    use UserTrait;

    public function serialize(): ?string
    {
        return null;
    }

    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }
}
