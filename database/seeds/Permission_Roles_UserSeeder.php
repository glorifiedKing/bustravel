<?php

use Illuminate\Database\Seeder;
use glorifiedking\BusTravel\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
class Permission_Roles_UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permision1 = factory(Permission::class)->create(['name' => 'Manage BT Stations']);
        $permision2 = factory(Permission::class)->create(['name' => 'Manage BT Permissions']);
        $permision3 = factory(Permission::class)->create(['name' => 'Manage BT Operator Settings']);
        $permision3 = factory(Permission::class)->create(['name' => 'Manage BT General Settings']);

    }
}
