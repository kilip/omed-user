<?php

declare(strict_types=1);

namespace Omed\User\Component\Tests;


use Exception;
use Omed\User\Contracts\Model\UserTrait;
use Omed\User\Contracts\Model\UserInterface;

class TestUserComponent implements UserInterface
{
    use UserTrait;

    protected string $id;

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
            $this->id
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
            $this->id,
            ) = $data;
    }

    public function getId(): string
    {
        return $this->id;
    }
}