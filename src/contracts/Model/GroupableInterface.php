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

interface GroupableInterface
{
    /**
     * Gets the groups granted to the user.
     *
     * @return array|GroupInterface[]
     */
    public function getGroups(): array;

    /**
     * Gets the name of the groups which includes the user.
     *
     * @return array|string[]
     */
    public function getGroupNames(): array;

    /**
     * Indicates whether the user belongs to the specified group or not.
     *
     * @param string $name Name of the group
     *
     * @return bool
     */
    public function hasGroup(string $name): bool;

    /**
     * Add a group to the user groups.
     */
    public function addGroup(GroupInterface $group): self;

    /**
     * Remove a group from the user groups.
     *
     * @return static
     */
    public function removeGroup(GroupInterface $group): self;
}
