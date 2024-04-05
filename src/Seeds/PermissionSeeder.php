<?php

namespace glorifiedking\BusTravel\Seeds;

use glorifiedking\BusTravel\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();
        $permission1 = Permission::create(['name' => 'Manage BT Stations']);
        $permission2 = Permission::create(['name' => 'Manage BT Permissions']);
        $permission3 = Permission::create(['name' => 'Manage BT Operator Settings']);
        $permission4 = Permission::create(['name' => 'Manage BT General Settings']);
        $permission5 = Permission::create(['name' => 'View BT Stations']);
        $permission6 = Permission::create(['name' => 'View BT Operators']);
        $permission7 = Permission::create(['name' => 'Create BT Operators']);
        $permission8 = Permission::create(['name' => 'Update BT Operators']);
        $permission11 = Permission::create(['name' => 'Delete BT Operators']);
        $permission12 = Permission::create(['name' => 'View BT Buses']);
        $permission13 = Permission::create(['name' => 'Create BT Buses']);
        $permission14 = Permission::create(['name' => 'Update BT Buses']);
        $permission15 = Permission::create(['name' => 'Delete BT Buses']);
        $permission16 = Permission::create(['name' => 'View BT Routes']);
        $permission17 = Permission::create(['name' => 'Create BT Routes']);
        $permission18 = Permission::create(['name' => 'Update BT Routes']);
        $permission19 = Permission::create(['name' => 'Delete BT Routes']);
        $permission20 = Permission::create(['name' => 'View BT Drivers']);
        $permission21 = Permission::create(['name' => 'Create BT Drivers']);
        $permission22 = Permission::create(['name' => 'Update BT Drivers']);
        $permission23 = Permission::create(['name' => 'Delete BT Drivers']);
        $permission24 = Permission::create(['name' => 'View BT Bookings']);
        $permission25 = Permission::create(['name' => 'Create BT Bookings']);
        $permission26 = Permission::create(['name' => 'Update BT Bookings']);
        $permission27 = Permission::create(['name' => 'Delete BT Bookings']);
        $permission28 = Permission::create(['name' => 'View BT Reports']);
        $permission29 = Permission::create(['name' => 'View BT Sales Reports']);
        $permission30 = Permission::create(['name' => 'View BT Payment Reports']);
        $permission31 = Permission::create(['name' => 'View BT Payments']);
        $permission32 = Permission::create(['name' => 'Create BT Payments']);
        $permission33 = Permission::create(['name' => 'Update BT Payments']);
        $permission34 = Permission::create(['name' => 'Delete BT Payments']);
        $permission35 = Permission::create(['name' => 'View BT Users']);
        $permission36 = Permission::create(['name' => 'Create BT Users']);
        $permission37 = Permission::create(['name' => 'Update BT Users']);
        $permission38 = Permission::create(['name' => 'Delete BT Users']);
        $permission39 = Permission::create(['name' => 'View BT Driver Manifest']);
        $permission40 = Permission::create(['name' => 'View BT Customer Transactions']);

        $role1 = Role::create(['name' => 'BT Super Admin']);
        $role1->givePermissionTo([
          $permission1, $permission2, $permission3, $permission4, $permission5, $permission6,
          $permission7, $permission8,  $permission11, $permission12,
          $permission13, $permission14, $permission15, $permission16, $permission17, $permission18,
          $permission19, $permission20, $permission21, $permission22, $permission23, $permission24,
          $permission25, $permission26, $permission27, $permission28, $permission29, $permission30,
          $permission31, $permission32, $permission33, $permission34, $permission35, $permission36,
          $permission37, $permission38,$permission39,$permission40,
        ]);
        $role2 = Role::create(['name' => 'BT Administrator']);
        $role2->givePermissionTo([
          $permission6, $permission5, $permission12, $permission13, $permission14, $permission15,
          $permission16, $permission17, $permission18, $permission19, $permission20, $permission21,
          $permission22, $permission23, $permission24, $permission25, $permission26, $permission27,
          $permission28, $permission29, $permission30, $permission31, $permission32, $permission33,
          $permission34, $permission35, $permission36, $permission37, $permission38,$permission39,
        ]);
        $role3 = Role::create(['name' => 'BT Cashier']);
        $role3->givePermissionTo([
          $permission24, $permission25, $permission28, $permission31, $permission32,
        ]);
        $role4 = Role::create(['name' => 'BT Merchant']);
        $role4->givePermissionTo([
          $permission24, $permission25, $permission31, $permission32,
        ]);

        $role5 = Role::create(['name' => 'BT Driver']);
        $role5->givePermissionTo([
          $permission24, $permission25, $permission31, $permission32,$permission39,
        ]);
        $role6 = Role::create(['name' => 'BT User']);
        $user = User::where('email', 'admin@admin.com')->first();
        if (is_null($user)) {
            $user1 = User::create([
            'name'        => 'BT Super Admin',
            'email'       => 'admin@admin.com',
            'password'    => Hash::make('password'), // password
            'operator_id' => 0,
          ]);
            $user1->syncRoles($role1->name);
        } else {
            $user->syncRoles($role1->name);
        }
    }
}
