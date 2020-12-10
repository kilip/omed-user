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

namespace Omed\User\Mezzio\Contracts;

use Omed\User\Contracts\ConfigurationInterface as BaseConfigurationInterface;
use Omed\User\Doctrine\Contracts\ConfigurationInterface as DoctrineConfiguration;

interface ConfigurationInterface extends BaseConfigurationInterface, DoctrineConfiguration
{
    public const CONFIG_KEY        = 'omed_user';
}
