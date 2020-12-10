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

namespace Omed\User\Mezzio\Exception;

use Exception;

class ObjectManagerException extends Exception
{
    public static function unknownClass(): self
    {
        $message = 'Unknown Object Manager class to use. '
            .'Please install and configure doctrine-orm or doctrine-mongodb-odm';

        return new self($message);
    }
}
