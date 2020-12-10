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

namespace Omed\User\Contracts\Model;

interface UserInterface extends \Serializable
{
    public function getUsername(): ?string;

    public function setUsername(string $username): self;

    public function setUsernameCanonical(string $usernameCanonical): self;

    public function getUsernameCanonical(): ?string;

    public function setEmail(string $email): self;

    public function getEmail(): ?string;

    public function setEmailCanonical(string $emailCanonical): self;

    public function getEmailCanonical(): ?string;

    public function setSalt(string $salt): self;

    public function getSalt(): ?string;

    public function setPassword(string $password): self;

    public function getPassword(): ?string;

    public function setPlainPassword(string $plainPassword): self;

    public function getPlainPassword(): ?string;
}
