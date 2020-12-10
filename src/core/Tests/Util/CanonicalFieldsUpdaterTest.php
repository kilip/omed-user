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

use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Util\CanonicalizerInterface;
use Omed\User\Core\Util\CanonicalFieldsUpdater;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CanonicalFieldsUpdaterTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var CanonicalizerInterface|ObjectProphecy
     */
    private $usernameCanonicalizer;
    /**
     * @var CanonicalizerInterface|ObjectProphecy
     */
    private $emailCanonicalizer;

    protected function setUp(): void
    {
        $this->usernameCanonicalizer = $this->prophesize(CanonicalizerInterface::class);
        $this->emailCanonicalizer    = $this->prophesize(CanonicalizerInterface::class);
    }

    private function getTarget(): CanonicalFieldsUpdater
    {
        return new CanonicalFieldsUpdater(
            $this->usernameCanonicalizer->reveal(),
            $this->emailCanonicalizer->reveal()
        );
    }

    public function testCanonicalizeUsername()
    {
        $this->usernameCanonicalizer->canonicalize('username')
            ->shouldBeCalled()
            ->willReturn('canonicalized');

        $this->assertSame(
            'canonicalized',
            $this->getTarget()->canonicalizeUsername('username')
        );
    }

    public function testCanonicalizeEmail()
    {
        $this->emailCanonicalizer->canonicalize('email')
            ->shouldBeCalled()
            ->willReturn('canonicalized');

        $this->assertSame(
            'canonicalized',
            $this->getTarget()->canonicalizeMail('email')
        );
    }

    public function testUpdateCanonicalFields()
    {
        $user = $this->prophesize(UserInterface::class);

        $user->getUsername()->shouldBeCalled()->willReturn('username');
        $user->getEmail()->shouldBeCalled()->willReturn('email');
        $user->setUsernameCanonical('canonicalized')->shouldBeCalled();
        $user->setEmailCanonical('canonicalized')->shouldBeCalled();

        $this->emailCanonicalizer
            ->canonicalize('email')
            ->shouldBeCalled()
            ->willReturn('canonicalized');

        $this->usernameCanonicalizer
            ->canonicalize('username')
            ->shouldBeCalled()
            ->willReturn('canonicalized');

        $this->getTarget()->updateCanonicalFields($user->reveal());
    }
}
