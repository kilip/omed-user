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

namespace Omed\User\Core\Util;

use Omed\User\Contracts\Util\EncoderFactoryInterface;
use Psr\Container\ContainerInterface;

class PasswordUpdaterFactory
{
    public function __invoke(ContainerInterface $container): PasswordUpdater
    {
        return new PasswordUpdater(
            $container->get(EncoderFactoryInterface::class)
        );
    }
}
