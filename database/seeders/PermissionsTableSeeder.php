<?php

namespace Database\Seeders;

use App\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Policy classes that the seeder should pick up.
     */
    protected $policies = [
        'api' => [
            \App\Policies\ProductPolicy::class,
            \App\Policies\InventoryPolicy::class,
        ],
    ];

    /**
     * Run the database seeds.
     * @usage php artisan db:seed --class=PermissionsTableSeeder
     * @return void
     */
    public function run()
    {
        foreach ($this->policies as $guard => $group) {
            foreach ($group as $policy) {
                if (empty($policy::PERMISSIONS)) {
                    continue;
                }

                foreach ($policy::PERMISSIONS as $permission) {
                    Permission::updateOrCreate(['name' => $permission, 'guard_name' => $guard]);
                }
            }
        }

        $roles = Role::guardName('api')->name('manager')->get();

        foreach ($roles as $role) {
            $role->givePermissionTo(Permission::guardName('api')->get());
        }
    }
}
