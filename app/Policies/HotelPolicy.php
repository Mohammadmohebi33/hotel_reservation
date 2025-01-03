<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\User;

class HotelPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function createRoom(User $user, Hotel $hotel)
    {
        return $user->id === $hotel->user_id;
    }
}
