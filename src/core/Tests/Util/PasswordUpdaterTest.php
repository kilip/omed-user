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
use Omed\User\Contracts\Util\EncoderFactoryInterface;
use Omed\User\Contracts\Util\EncoderInterface;
use Omed\User\Core\Util\PasswordUpdater;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class PasswordUpdaterTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var EncoderFactoryInterface|ObjectProphecy
     */
    private $encoderFactory;

    protected function setUp(): void
    {
        $this->encoderFactory = $this->prophesize(EncoderFactoryInterface::class);
    }

    public function getTarget(): PasswordUpdater
    {
        return new PasswordUpdater($this->encoderFactory->reveal());
    }

    public function testHashWithNullPlainPassword()
    {
        $user = $this->prophesize(UserInterface::class);

        $user->getPlainPassword()
            ->willReturn(null)
            ->shouldBeCalled();

        $this->getTarget()->hashPassword($user->reveal());
    }

    public function testSuccessfullyHashPassword()
    {
        $user    = $this->prophesize(UserInterface::class);
        $encoder = $this->prophesize(EncoderInterface::class);

        $user->getPlainPassword()
            ->shouldBeCalled()
            ->willReturn('password');
        $user->setSalt(Argument::type('string'))
            ->shouldBeCalled();
        $user->getSalt()
            ->shouldBeCalled()
            ->willReturn('salt');

        $this->encoderFactory->getEncoder($user->reveal())
            ->shouldBeCalled()
            ->willReturn($encoder);
        $encoder->encodePassword('password', 'salt')
            ->shouldBeCalled()
            ->willReturn('encoded');

        $user->setPassword('encoded')
            ->shouldBeCalled()
            ->willReturn($user);
        $user->eraseCredentials()->shouldBeCalled();

        $this->getTarget()->hashPassword($user->reveal());
    }
}
