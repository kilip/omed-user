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

namespace Omed\User\Mezzio\Tests\Integration;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use DoctrineORMModule\ConfigProvider;
use Laminas\ConfigAggregator\ArrayProvider;
use Omed\User\Doctrine\Constant;
use Omed\User\Mezzio\Contracts\ConfigurationInterface;
use Omed\User\Mezzio\Tests\Integration\ORM\Model\TestUser;

abstract class ORMTestCase extends IntegrationTestCase
{
    public static function configure(): void
    {
        static::addProvider(ConfigProvider::class);

        $doctrine = new ArrayProvider([
            ConfigurationInterface::CONFIG_KEY => [
                'user_class' => TestUser::class,
            ],
            'doctrine' => [
                'driver' => [
                    'omed_xml' => [
                        'class' => SimplifiedXmlDriver::class,
                        'paths' => [
                            Constant::getModelConfigDir() => Constant::getModelNamespace(),
                        ],
                    ],
                    'test_annotation' => [
                        'class' => AnnotationDriver::class,
                        'paths' => [
                            __DIR__.'/ORM/Model',
                        ],
                    ],
                    'orm_default' => [
                        'drivers' => [
                            __NAMESPACE__.'\\ORM\\Model' => 'test_annotation',
                            Constant::getModelNamespace() => 'omed_xml',
                        ],
                    ],
                ],
            ],
        ]);
        static::addProvider($doctrine);
    }
}
