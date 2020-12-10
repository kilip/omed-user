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

namespace Omed\User\Core\Util;

use Exception;
use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Util\EncoderFactoryInterface;
use Omed\User\Contracts\Util\PasswordUpdaterInterface;

class PasswordUpdater implements PasswordUpdaterInterface
{
    /**
     * @var \Omed\User\Contracts\Util\EncoderFactoryInterface
     */
    private EncoderFactoryInterface $factory;

    public function __construct(EncoderFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param UserInterface $user
     *
     * @throws Exception On failed salt generator
     */
    public function hashPassword(UserInterface $user): void
    {
        $plainPassword = $user->getPlainPassword();

        if (null === $plainPassword) {
            return;
        }

        $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
        $user->setSalt($salt);

        $encoder        = $this->factory->getEncoder($user);
        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());

        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}
