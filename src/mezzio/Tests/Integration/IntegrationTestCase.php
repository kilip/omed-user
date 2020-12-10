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

use Omed\User\Mezzio\Testing\InteractsWithApplication;
use PHPUnit\Framework\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    use InteractsWithApplication;

    public static function setUpBeforeClass(): void
    {
        static::initialize();
    }

    public static function tearDownAfterClass(): void
    {
        static::$container = null;
        static::$app       = null;
    }
}
