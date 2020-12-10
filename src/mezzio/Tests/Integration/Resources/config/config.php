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

use Laminas\ConfigAggregator\ConfigAggregator;
use Omed\User;

$aggregator = new ConfigAggregator([
    Mezzio\ConfigProvider::class,
    DoctrineModule\ConfigProvider::class,
    User\Mezzio\ConfigProvider::class,
]);

return $aggregator->getMergedConfig();
