<?php

namespace App\Queries\Interfaces;

use App\Entities\User;

/**
 *
 * @author Hristo
 */
interface UserQueryInterface
{
    public function all(): array;
}
