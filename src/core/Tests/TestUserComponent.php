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

namespace Omed\User\Core\Tests;

use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Model\UserTrait;

class TestUserComponent implements UserInterface
{
    use UserTrait;

    protected ?string $id = null;

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->email,
            $this->emailCanonical,
            $this->id,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        $data = unserialize($serialized);

        list(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->email,
            $this->emailCanonical,
            $this->id) = $data;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
