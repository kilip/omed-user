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

use Omed\User\Contracts\Manager\UserManagerInterface;
use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Doctrine\Manager\UserManager;

class FactoriesTest extends ORMTestCase
{
    /**
     * @dataProvider ensureServiceProvider
     */
    public function testEnsureServiceCreated(string $serviceId, string $expectedClass)
    {
        $result = $this->getService($serviceId);
        $this->assertInstanceOf($expectedClass, $result);
    }

    public function ensureServiceProvider(): array
    {
        return [
            [UserManagerInterface::class, UserManager::class],
        ];
    }

    public function testEncodePassword()
    {
        /** @var UserManagerInterface $userManager */
        $userManager = $this->getService(UserManagerInterface::class);
        $user        = $userManager->createUser();

        $this->assertInstanceOf(UserInterface::class, $user);

        $user->setPlainPassword('plain');
        $userManager->updatePassword($user);

        $this->assertNull($user->getPlainPassword());
        $this->assertNotNull($user->getPassword());
    }
}
