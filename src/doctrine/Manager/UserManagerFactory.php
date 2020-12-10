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

namespace Omed\User\Doctrine\Manager;

use Omed\User\Contracts\ConfigurationInterface as CoreConfiguration;
use Omed\User\Contracts\Manager\UserManagerInterface;
use Omed\User\Contracts\Util\CanonicalFieldsUpdaterInterface;
use Omed\User\Contracts\Util\PasswordUpdaterInterface;
use Omed\User\Doctrine\Contracts\ConfigurationInterface as DoctrineConfiguration;
use Psr\Container\ContainerInterface;

class UserManagerFactory
{
    public function __invoke(ContainerInterface $container): UserManagerInterface
    {
        $doctrineConfig = $container->get(DoctrineConfiguration::class);
        $coreConfig     = $container->get(CoreConfiguration::class);

        return new UserManager(
            $container->get($doctrineConfig->getObjectManagerClass()),
            $container->get(PasswordUpdaterInterface::class),
            $container->get(CanonicalFieldsUpdaterInterface::class),
            $coreConfig->getUserClass()
        );
    }
}
