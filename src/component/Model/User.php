<?php

declare(strict_types=1);

namespace Omed\User\Component\Model;


use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Contracts\Model\UserTrait;

abstract class User implements UserInterface
{
    use UserTrait;
}