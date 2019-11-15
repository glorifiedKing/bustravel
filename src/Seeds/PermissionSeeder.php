<?php
namespace glorifiedking\BusTravel\Seeds;
use Illuminate\Database\Seeder;
use glorifiedking\BusTravel\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission1 = factory(Permission::class)->create(['name' => 'Manage BT Stations']);
        $permission2 = factory(Permission::class)->create(['name' => 'Manage BT Permissions']);
        $permission3 = factory(Permission::class)->create(['name' => 'Manage BT Operator Settings']);
        $permission4 = factory(Permission::class)->create(['name' => 'Manage BT General Settings']);
        $permission5 = factory(Permission::class)->create(['name' => 'View BT Stations']);
        $permission6 = factory(Permission::class)->create(['name' => 'View BT Operators']);
        $permission7 = factory(Permission::class)->create(['name' => 'Create BT Operators']);
        $permission8 = factory(Permission::class)->create(['name' => 'Update BT Operators']);
        $permission9 = factory(Permission::class)->create(['name' => 'Manage BT Stations']);
        $permission10 = factory(Permission::class)->create(['name' => 'Manage BT Permissions']);
        $permission11 = factory(Permission::class)->create(['name' => 'Delete BT Operators']);
        $permission12 = factory(Permission::class)->create(['name' => 'View BT Buses']);
        $permission13 = factory(Permission::class)->create(['name' => 'Create BT Buses']);
        $permission14 = factory(Permission::class)->create(['name' => 'Update BT Buses']);
        $permission15 = factory(Permission::class)->create(['name' => 'Delete BT Buses']);
        $permission16 = factory(Permission::class)->create(['name' => 'View BT Routes']);
        $permission17 = factory(Permission::class)->create(['name' => 'Create BT Routes']);
        $permission18 = factory(Permission::class)->create(['name' => 'Update BT Routes']);
        $permission19 = factory(Permission::class)->create(['name' => 'Delete BT Routes']);
        $permission20 = factory(Permission::class)->create(['name' => 'View BT Drivers']);
        $permission21 = factory(Permission::class)->create(['name' => 'Create BT Drivers']);
        $permission22 = factory(Permission::class)->create(['name' => 'Update BT Drivers']);
        $permission23 = factory(Permission::class)->create(['name' => 'Delete BT Drivers']);
        $permission24 = factory(Permission::class)->create(['name' => 'View BT Bookings']);
        $permission25 = factory(Permission::class)->create(['name' => 'Create BT Bookings']);
        $permission26 = factory(Permission::class)->create(['name' => 'Update BT Bookings']);
        $permission27 = factory(Permission::class)->create(['name' => 'Delete BT Bookings']);
        $permission28= factory(Permission::class)->create(['name' => 'View BT Reports']);
        $permission29 = factory(Permission::class)->create(['name' => 'View BT Sales Reports']);
        $permission30 = factory(Permission::class)->create(['name' => 'View BT Payment Reports']);
        $permission31 = factory(Permission::class)->create(['name' => 'View BT Payments']);
        $permission32 = factory(Permission::class)->create(['name' => 'Create BT Payments']);
        $permission33 = factory(Permission::class)->create(['name' => 'Update BT Payments']);
        $permission34 = factory(Permission::class)->create(['name' => 'Delete BT Payments']);
        $permission35 = factory(Permission::class)->create(['name' => 'View BT Users']);
        $permission36 = factory(Permission::class)->create(['name' => 'Create BT Users']);
        $permission37 = factory(Permission::class)->create(['name' => 'Update BT Users']);
        $permission38 = factory(Permission::class)->create(['name' => 'Delete BT Users']);

        $role1= factory(Role::class)->create(['name' => 'BT Super Admin']);
        $role1->givePermissionTo([
          $permission1, $permission2, $permission3,$permission4, $permission5, $permission6,
          $permission7, $permission8, $permission9,$permission10, $permission11, $permission12,
          $permission13, $permission14, $permission15,$permission16, $permission17, $permission18,
          $permission19, $permission20, $permission21,$permission22, $permission23, $permission24,
          $permission25, $permission26, $permission27,$permission28, $permission29, $permission30,
          $permission31, $permission32, $permission33,$permission34, $permission35, $permission36,
          $permission37, $permission38,
        ]);
        $role2= factory(Role::class)->create(['name' => 'BT Administrator']);
        $role2->givePermissionTo([
          $permission6, $permission5, $permission12,$permission13, $permission14, $permission15,
          $permission16, $permission17, $permission18,$permission19, $permission20, $permission21,
          $permission22, $permission23, $permission24,$permission25, $permission26, $permission27,
          $permission28, $permission29, $permission30,$permission31, $permission32, $permission33,
          $permission34, $permission35, $permission36,$permission37, $permission38
        ]);
        $role3= factory(Role::class)->create(['name' => 'BT Cashier']);
        $role3->givePermissionTo([
          $permission24, $permission25,$permission28, $permission31,$permission32
        ]);
        $role4= factory(Role::class)->create(['name' => 'BT Merchant']);
        $role4->givePermissionTo([
          $permission24, $permission25, $permission31,$permission32
        ]);

        $role5= factory(Role::class)->create(['name' => 'BT Driver']);
        $role5->givePermissionTo([
          $permission24, $permission25, $permission31,$permission32
        ]);
        $role6= factory(Role::class)->create(['name' => 'BT User']);
         $user =User::where('email','admin@admin.com')->first();
        if(is_null($user))
        {
          $user1 = factory(User::class)->create([
            'name' => 'BT Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'), // password
            'operator_id' => 0,
          ]);
          $user1->syncRoles($role1->name);
        }else{
          $user->syncRoles($role1->name);
        }

    }

}
