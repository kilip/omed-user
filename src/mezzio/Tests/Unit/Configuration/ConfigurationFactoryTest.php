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

namespace Omed\User\Mezzio\Tests\Unit\Configuration;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Omed\User\Mezzio\Configuration\ConfigurationFactory;
use Omed\User\Mezzio\Contracts\ConfigurationInterface;
use Omed\User\Mezzio\Exception;
use Omed\User\Mezzio\Tests\Unit\TestUser;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;

class ConfigurationFactoryTest extends TestCase
{
    use ProphecyTrait;

    protected function generateDefaultConfig(): array
    {
        return [
            ConfigurationInterface::CONFIG_KEY => [
                'user_class' => TestUser::class,
            ],
        ];
    }

    /**
     * @param string $expected
     * @param string $method
     *
     * @throws Exception\ObjectManagerException
     * @throws Exception\UserClassException
     * @dataProvider successfulProvider
     */
    public function testCreateConfigurationSuccessfully(string $expected, string $method)
    {
        $config = $this->generateDefaultConfig();

        $container = $this->prophesize(ContainerInterface::class);
        $container->has(EntityManager::class)
            ->shouldBeCalled()
            ->willReturn(true);

        $container->get('config')
            ->willReturn($config)
            ->shouldBeCalled();

        $configuration = (new ConfigurationFactory())($container->reveal());

        $this->assertSame(
            $expected,
            \call_user_func([$configuration, $method])
        );
    }

    public function successfulProvider(): array
    {
        return [
            [TestUser::class, 'getUserClass'],
            [EntityManager::class, 'getObjectManagerClass'],
        ];
    }

    public function testWithUndefinedUserClass()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')
            ->willReturn([ConfigurationInterface::CONFIG_KEY => []])
            ->shouldBeCalled();

        $e = Exception\UserClassException::undefined();
        $this->expectException(Exception\UserClassException::class);
        $this->expectExceptionMessage($e->getMessage());
        (new ConfigurationFactory())($container->reveal());
    }

    public function testWithNonExistentUserClass()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')
            ->willReturn([
                ConfigurationInterface::CONFIG_KEY => [
                    'user_class' => 'Non\\Existent',
                ],
            ])
            ->shouldBeCalled();

        $e = Exception\UserClassException::classNotExists('Non\\Existent');
        $this->expectException(Exception\UserClassException::class);
        $this->expectExceptionMessage($e->getMessage());
        (new ConfigurationFactory())($container->reveal());
    }

    public function testWithIncompatibleClass()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')
            ->willReturn([
                ConfigurationInterface::CONFIG_KEY => [
                    'user_class' => __CLASS__,
                ],
            ])
            ->shouldBeCalled();

        $e = Exception\UserClassException::incompatible(__CLASS__);

        $this->expectException(Exception\UserClassException::class);
        $this->expectExceptionMessage($e->getMessage());
        (new ConfigurationFactory())($container->reveal());
    }

    public function testWithMongoDocumentManager()
    {
        $config    = $this->generateDefaultConfig();
        $container = $this->prophesize(ContainerInterface::class);

        $container->get('config')->willReturn($config);

        $container->has(EntityManager::class)
            ->shouldBeCalled()
            ->willReturn(false);
        $container->has(DocumentManager::class)
            ->shouldBeCalled()
            ->willReturn(true);

        $configuration = (new ConfigurationFactory())($container->reveal());

        $this->assertSame(
            DocumentManager::class,
            $configuration->getObjectManagerClass()
        );
    }

    public function testWithUnknownObjectManager()
    {
        $config    = $this->generateDefaultConfig();
        $container = $this->prophesize(ContainerInterface::class);

        $container->get('config')->willReturn($config);

        $container->has(EntityManager::class)->willReturn(false);
        $container->has(DocumentManager::class)->willReturn(false);

        $e = Exception\ObjectManagerException::unknownClass();
        $this->expectException(Exception\ObjectManagerException::class);
        $this->expectExceptionMessage($e->getMessage());

        (new ConfigurationFactory())($container->reveal());
    }
}
