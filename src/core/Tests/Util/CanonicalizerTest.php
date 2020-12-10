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

namespace Omed\User\Core\Tests\Util;

use Omed\User\Core\Util\Canonicalizer;
use PHPUnit\Framework\TestCase;
use const PHP_VERSION_ID;

class CanonicalizerTest extends TestCase
{
    /**
     * @param string $source
     * @param string $expected
     * @dataProvider canonicalizeProvider
     */
    public function testCanonicalize(string $source, string $expected)
    {
        $canonicalizer = new Canonicalizer();
        $this->assertSame(
            $expected,
            $canonicalizer->canonicalize($source)
        );
    }

    public function canonicalizeProvider(): array
    {
        return [
            ['FOO', 'foo'],
            [\chr(171), PHP_VERSION_ID < 50600 ? \chr(171) : '?'],
        ];
    }
}
