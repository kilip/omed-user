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

use Omed\User\Contracts\ConfigurationInterface;
use Psr\Container\ContainerInterface;

class CanonicalFieldsUpdaterFactory
{
    public function __invoke(ContainerInterface $container): CanonicalFieldsUpdater
    {
        $config = $container->get(ConfigurationInterface::class);

        return new CanonicalFieldsUpdater(
            $container->get($config->getUsernameCanonicalizer()),
            $container->get($config->getEmailCanonicalizer())
        );
    }
}
