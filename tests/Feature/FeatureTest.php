<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    use DatabaseTransactions;

    protected function auth(): self
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        return $this;
    }

    protected function authAsAdminRole(): self
    {
        $admin = Admin::factory()->create();
        $role = Role::factory()->create(['name' => 'admin']);
        $admin->roles()->attach($role);

        Sanctum::actingAs($admin);

        return $this;
    }
}
