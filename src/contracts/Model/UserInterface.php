<?php

declare(strict_types=1);

namespace Omed\User\Contracts\Model;


interface UserInterface extends \Serializable
{
    public function getUsername();

    public function setUsername(string $username);

    public function setUsernameCanonical(string $usernameCanonical);

    public function getUsernameCanonical(): ?string;

    public function setEmail(string $email);

    public function getEmail(): ?string;

    public function setEmailCanonical(string $emailCanonical);

    public function getEmailCanonical(): ?string;

    public function setSalt(string $salt);

    public function getSalt(): ?string;

    public function setPassword(string $password);

    public function getPassword(): ?string;

    public function setPlainPassword(string $plainPassword);

    public function getPlainPassword(): ?string;
}