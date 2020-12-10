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
use Doctrine\Persistence\ObjectRepository;
use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Util\CanonicalFieldsUpdaterInterface;
use Omed\User\Contracts\Util\PasswordUpdaterInterface;
use Omed\User\Core\Tests\TestUserComponent;
use Omed\User\Doctrine\Manager\UserManager;
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
     * @var ObjectManager|ObjectProphecy
     */
    private $om;
    /**
     * @var ObjectRepository|ObjectProphecy
     */
    private $objectRepository;

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
