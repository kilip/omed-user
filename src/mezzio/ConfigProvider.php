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

namespace Omed\User\Mezzio;

use Omed\User\Contracts;
use Omed\User\Core;
use Omed\User\Doctrine;
use Omed\User\Mezzio;
use Omed\User\Mezzio\Util as MezzioUtil;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            Mezzio\Contracts\ConfigurationInterface::CONFIG_KEY => $this->getDefaults(),
            'dependencies' => $this->getDependencies(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            'aliases' => [
                Doctrine\Contracts\ConfigurationInterface::class => Configuration\Configuration::class,
                Contracts\ConfigurationInterface::class => Configuration\Configuration::class,
                Contracts\Manager\UserManagerInterface::class => Doctrine\Manager\UserManager::class,

                // utilities
                Contracts\Util\CanonicalizerInterface::class => Core\Util\Canonicalizer::class,
                Contracts\Util\CanonicalFieldsUpdaterInterface::class => Core\Util\CanonicalFieldsUpdater::class,
                Contracts\Util\PasswordUpdaterInterface::class => Core\Util\PasswordUpdater::class,
                Contracts\Util\EncoderFactoryInterface::class => MezzioUtil\EncoderFactory::class,
            ],
            'factories' => [
                Doctrine\Manager\UserManager::class => Doctrine\Manager\UserManagerFactory::class,
                Configuration\Configuration::class => Configuration\ConfigurationFactory::class,
                MezzioUtil\EncoderFactory::class => function () {
                    return new MezzioUtil\EncoderFactory();
                },
                Core\Util\PasswordUpdater::class => Core\Util\PasswordUpdaterFactory::class,
                Core\Util\Canonicalizer::class => Core\Util\CanonicalizerFactory::class,
                Core\Util\CanonicalFieldsUpdater::class => Core\Util\CanonicalFieldsUpdaterFactory::class,
            ],
        ];
    }

    private function getDefaults(): array
    {
        return [
            'username_canonicalizer' => Contracts\Util\CanonicalizerInterface::class,
            'email_canonicalizer' => Contracts\Util\CanonicalizerInterface::class,
        ];
    }
}
