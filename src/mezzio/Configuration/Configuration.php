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

namespace Omed\User\Mezzio\Configuration;

use Laminas\Stdlib\AbstractOptions;
use Omed\User\Mezzio\Contracts\ConfigurationInterface;

class Configuration extends AbstractOptions implements ConfigurationInterface
{
    private ?string $userClass             = null;
    private ?string $objectManagerClass    = null;
    private ?string $usernameCanonicalizer = null;
    private ?string $emailCanonicalizer    = null;

    public function setEmailCanonicalizer(string $serviceId): void
    {
        $this->emailCanonicalizer = $serviceId;
    }

    public function getEmailCanonicalizer(): ?string
    {
        return $this->emailCanonicalizer;
    }

    public function setUsernameCanonicalizer(string $serviceId): void
    {
        $this->usernameCanonicalizer = $serviceId;
    }

    public function getUsernameCanonicalizer(): ?string
    {
        return $this->usernameCanonicalizer;
    }

    public function setObjectManagerClass(string $class): void
    {
        $this->objectManagerClass = $class;
    }

    public function getObjectManagerClass(): ?string
    {
        return $this->objectManagerClass;
    }

    public function setUserClass(string $userClass): void
    {
        $this->userClass = $userClass;
    }

    public function getUserClass(): ?string
    {
        return $this->userClass;
    }
}
