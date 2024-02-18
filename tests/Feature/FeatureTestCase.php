<?php

namespace Tests\Feature;

use App\Enum\Role as RoleEnum;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeatureTestCase extends TestCase
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
        $role = Role::factory()->create(['name' => RoleEnum::ADMIN->value]);
        $admin->roles()->attach($role);

        Sanctum::actingAs($admin);

        return $this;
    }

    protected function authAsEditorRole(): self
    {
        $admin = Admin::factory()->create();
        $role = Role::factory()->create(['name' => RoleEnum::EDITOR->value]);
        $admin->roles()->attach($role);

        Sanctum::actingAs($admin);

        return $this;
    }
}
