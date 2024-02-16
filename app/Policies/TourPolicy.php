<?php

namespace App\Policies;

use App\Models\Admin;

class TourPolicy
{
    public function create(Admin $admin): bool
    {
        return $admin->isAdmin();
    }
}
