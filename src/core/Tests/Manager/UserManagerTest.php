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

namespace Omed\User\Core\Tests\Manager;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Updater\CanonicalFieldsUpdaterInterface;
use Omed\User\Contracts\Updater\PasswordUpdaterInterface;
use Omed\User\Core\Manager\UserManager;
use Omed\User\Core\Tests\TestRepository;
use Omed\User\Core\Tests\TestUserComponent;
use Omed\User\Core\Tests\TestUserManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class UserManagerTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var PasswordUpdaterInterface|ObjectProphecy
     */
    private $passwordUpdater;

    /**
     * @var CanonicalFieldsUpdaterInterface|ObjectProphecy
     */
    private $canonicalUpdater;

    /**
     * @var ObjectRepository|ObjectProphecy
     */
    private $objectRepository;

    /**
     * @var ObjectManager|ObjectProphecy
     */
    private $om;
    /**
     * @var TestRepository|ObjectProphecy
     */
    private $repository;

    protected function setUp(): void
    {
        $this->passwordUpdater  = $this->prophesize(PasswordUpdaterInterface::class);
        $this->canonicalUpdater = $this->prophesize(CanonicalFieldsUpdaterInterface::class);
        $this->repository       = $this->prophesize(TestRepository::class);
    }

    protected function getTarget(): UserManager
    {
        return new TestUserManager(
            $this->repository->reveal(),
            $this->passwordUpdater->reveal(),
            $this->canonicalUpdater->reveal(),
            TestUserComponent::class
        );
    }

    public function testCreateUser()
    {
        $result = $this->getTarget()->createUser();

        $this->assertInstanceOf(TestUserComponent::class, $result);
    }

    public function testFindByUsername()
    {
        $username = 'test';
        $user     = $this->prophesize(UserInterface::class)->reveal();

        $this->canonicalUpdater->canonicalizeUsername($username)
            ->shouldBeCalled()
            ->willReturn('canonicalized');
        $this->repository->findBy(['usernameCanonical' => 'canonicalized'])
            ->shouldBeCalled()
            ->willReturn($user);

        $this->assertSame(
            $user,
            $this->getTarget()->findByUsername($username)
        );
    }

    public function testFindByEmail()
    {
        $email = 'test@example.com';
        $user     = $this->prophesize(UserInterface::class)->reveal();

        $this->canonicalUpdater->canonicalizeMail($email)
            ->shouldBeCalled()
            ->willReturn('canonicalized');

        $this->repository->findBy(['emailCanonical' => 'canonicalized'])
            ->shouldBeCalled()
            ->willReturn($user);
        $this->assertSame(
            $user,
            $this->getTarget()->findByEmail($email)
        );
    }

    public function testFindByUsernameOrEmailWithEmail()
    {
        $user     = $this->prophesize(UserInterface::class)->reveal();

        $this->canonicalUpdater->canonicalizeMail('test@example.com')
            ->shouldBeCalled()
            ->willReturn('canonicalized');

        $this->repository->findBy(['emailCanonical' => 'canonicalized'])
            ->shouldBeCalled()
            ->willReturn($user);

        $this->assertSame(
            $user,
            $this->getTarget()->findByUsernameOrEmail('test@example.com')
        );
    }

    public function testFindByUsernameOrEmailWithUsername()
    {
        $user = $this->prophesize(UserInterface::class)->reveal();

        $this->canonicalUpdater
            ->canonicalizeUsername('username')
            ->shouldBeCalled()
            ->willReturn('canonicalized');

        $this->repository->findBy(['usernameCanonical' => 'canonicalized'])
            ->shouldBeCalled()
            ->willReturn($user);

        $this->assertSame(
            $user,
            $this->getTarget()->findByUsernameOrEmail('username')
        );

    }

    public function testUpdateCanonicalFields()
    {
        $user = $this->prophesize(UserInterface::class)->reveal();

        $this->canonicalUpdater
            ->updateCanonicalFields($user)
            ->shouldBeCalled();

        $this->getTarget()->updateCanonicalFields($user);
    }

    public function testUpdatePassword()
    {
        $user = $this->prophesize(UserInterface::class)->reveal();

        $this->passwordUpdater
            ->hashPassword($user)
            ->shouldBeCalled();

        $this->getTarget()->updatePassword($user);
    }
}
