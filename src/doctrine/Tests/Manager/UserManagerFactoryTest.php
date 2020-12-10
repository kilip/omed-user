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

namespace Omed\User\Doctrine\Tests\Manager;

use Doctrine\Persistence\ObjectManager;
use Omed\User\Contracts\ConfigurationInterface as CoreConfig;
use Omed\User\Contracts\Util\CanonicalFieldsUpdaterInterface;
use Omed\User\Contracts\Util\PasswordUpdaterInterface;
use Omed\User\Doctrine\Contracts\ConfigurationInterface as DoctrineConfig;
use Omed\User\Doctrine\Manager\UserManagerFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;

class UserManagerFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testCreate()
    {
        $container        = $this->prophesize(ContainerInterface::class);
        $doctrineConfig   = $this->prophesize(DoctrineConfig::class);
        $coreConfig       = $this->prophesize(CoreConfig::class);
        $passwordUpdater  = $this->prophesize(PasswordUpdaterInterface::class)->reveal();
        $canonicalUpdater = $this->prophesize(CanonicalFieldsUpdaterInterface::class)->reveal();
        $om               = $this->prophesize(ObjectManager::class)->reveal();

        $container->get(DoctrineConfig::class)
            ->shouldBeCalled()
            ->willReturn($doctrineConfig);
        $container->get(CoreConfig::class)
            ->shouldBeCalled()
            ->willReturn($coreConfig);

        $doctrineConfig->getObjectManagerClass()
            ->shouldBeCalled()
            ->willReturn(ObjectManager::class);
        $container->get(ObjectManager::class)
            ->shouldBeCalled()
            ->willReturn($om);
        $container->get(PasswordUpdaterInterface::class)
            ->shouldBeCalled()
            ->willReturn($passwordUpdater);
        $container->get(CanonicalFieldsUpdaterInterface::class)
            ->shouldBeCalled()
            ->willReturn($canonicalUpdater);
        $coreConfig->getUserClass()
            ->shouldBeCalled()
            ->willReturn(__CLASS__);

        (new UserManagerFactory())($container->reveal());
    }
}
