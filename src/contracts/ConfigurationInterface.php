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

namespace Omed\User\Contracts;

interface ConfigurationInterface
{
    /**
     * Set email canonicalizer service id.
     */
    public function setEmailCanonicalizer(string $serviceId): void;

    /**
     * Get email canonicalizer service id.
     */
    public function getEmailCanonicalizer(): ?string;

    /**
     * Set username canonicalizer service id.
     */
    public function setUsernameCanonicalizer(string $serviceId): void;

    /**
     * Get username canonicalizer service id.
     */
    public function getUsernameCanonicalizer(): ?string;

    public function setUserClass(string $userClass): void;

    public function getUserClass(): ?string;
}
