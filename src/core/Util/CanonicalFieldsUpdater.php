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

use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Util\CanonicalFieldsUpdaterInterface;
use Omed\User\Contracts\Util\CanonicalizerInterface;

class CanonicalFieldsUpdater implements CanonicalFieldsUpdaterInterface
{
    /**
     * @var CanonicalizerInterface
     */
    private CanonicalizerInterface $usernameCanonicalizer;
    /**
     * @var CanonicalizerInterface
     */
    private CanonicalizerInterface $emailCanonicalizer;

    public function __construct(
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer
    ) {
        $this->usernameCanonicalizer = $usernameCanonicalizer;
        $this->emailCanonicalizer    = $emailCanonicalizer;
    }

    public function canonicalizeUsername(string $username): string
    {
        return $this->usernameCanonicalizer->canonicalize($username);
    }

    public function canonicalizeMail(string $email): string
    {
        return $this->emailCanonicalizer->canonicalize($email);
    }

    public function updateCanonicalFields(UserInterface $user): void
    {
        $user->setUsernameCanonical($this->canonicalizeUsername($user->getUsername()));
        $user->setEmailCanonical($this->canonicalizeMail($user->getEmail()));
    }
}
