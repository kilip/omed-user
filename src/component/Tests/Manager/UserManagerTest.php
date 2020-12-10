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

namespace Omed\User\Component\Tests\Manager;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Omed\User\Component\Manager\UserManager;
use Omed\User\Component\Tests\TestUserComponent;
use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Updater\CanonicalFieldsUpdaterInterface;
use Omed\User\Contracts\Updater\PasswordUpdaterInterface;
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

    protected function setUp(): void
    {
        $this->passwordUpdater  = $this->prophesize(PasswordUpdaterInterface::class);
        $this->canonicalUpdater = $this->prophesize(CanonicalFieldsUpdaterInterface::class);
        $this->om               = $this->prophesize(ObjectManager::class);
        $this->objectRepository = $this->prophesize(ObjectRepository::class);

        $this->om
            ->getRepository(TestUserComponent::class)
            ->willReturn($this->objectRepository);
    }

    protected function getTarget(): UserManager
    {
        return new UserManager(
            $this->om->reveal(),
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

    public function testFindBy()
    {
        $criteria = ['foo' => 'bar'];
        $user     = $this->prophesize(UserInterface::class)->reveal();

        $this->objectRepository
            ->findOneBy($criteria)
            ->shouldBeCalled()
            ->willReturn($user);

        $this->assertSame(
            $user,
            $this->getTarget()->findBy($criteria)
        );
    }

    public function testFindByUsername()
    {
        $username = 'test';
        $user     = $this->prophesize(UserInterface::class);

        $this->canonicalUpdater->canonicalizeUsername($username)
            ->shouldBeCalled()
            ->willReturn('canonicalized');

        $this->objectRepository
            ->findOneBy(['usernameCanonical' => 'canonicalized'])
            ->shouldBeCalled()
            ->willReturn($user);

        $this->getTarget()->findByUsername($username);
    }

    public function testFindByEmail()
    {
        $email = 'test@example.com';
        $user  = $this->prophesize(UserInterface::class)->reveal();

        $this->canonicalUpdater->canonicalizeMail($email)
            ->shouldBeCalled()
            ->willReturn('canonicalized');

        $this->objectRepository
            ->findOneBy(['emailCanonical' => 'canonicalized'])
            ->shouldBeCalled()
            ->willReturn($user);

        $this->assertSame(
            $user,
            $this->getTarget()->findByEmail($email)
        );
    }

    public function testFindByUsernameOrEmailWithEmail()
    {
        $user = $this->prophesize(UserInterface::class)->reveal();

        $this->canonicalUpdater->canonicalizeMail('test@example.com')
            ->shouldBeCalled()
            ->willReturn('canonicalized');

        $this->objectRepository
            ->findOneBy(['emailCanonical' => 'canonicalized'])
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

        $this->objectRepository
            ->findOneBy(['usernameCanonical' => 'canonicalized'])
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

    public function testDeleteUser()
    {
        $user = $this->prophesize(UserInterface::class)->reveal();

        $this->om->remove($user)->shouldBeCalled();
        $this->om->flush()->shouldBeCalled();

        $this->getTarget()->deleteUser($user);
    }

    public function testSave()
    {
        $user = $this->prophesize(UserInterface::class)->reveal();

        $this->om->persist($user)->shouldBeCalled();
        $this->om->flush()->shouldBeCalled();

        $this->getTarget()->save($user);
    }
}
