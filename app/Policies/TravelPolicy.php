<?php

namespace App\Policies;

use App\Models\Admin;

class TravelPolicy
{
    public function create(Admin $admin): bool
    {
        return $admin->isAdmin();
    }

    public function update(Admin $admin): bool
    {
        return $admin->isEditor();
    }
}
