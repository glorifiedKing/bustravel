<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use glorifiedking\BusTravel\ToastNotification;
use glorifiedking\BusTravel\GeneralSetting;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        //$this->middleware('can:View BT Stations');
        $this->middleware('can:Manage BT Permissions')->only('permissions','storepermissions','updatepermissions','deletepermissions','roles','createroles','storeroles','editroles','updateroles','deleteroles');
        $this->middleware('can:View BT Users')->only('users','createusers','storeusers','editusers','updateusers','deleteusers');
    }

    //fetching permissions
    public function permissions()
    {
        $permissions = Permission::all();

        return view('bustravel::backend.users.permissions.index', compact('permissions'));
    }

    // save Permission
    public function storepermissions(Request $request)
    {
        //validation
        //saving to the database
        $permission = new Permission();
        $permission->name = request()->input('name');
        $permission->guard_name = request()->input('guard_name') ?? 'web';
        $permission->save();

        return redirect()->route('bustravel.users.permissions')->with(ToastNotification::toast($permission->name.' has successfully been saved','Permission Saving'));
    }

    //Upadte Permission
    public function updatepermissions($id, Request $request)
    {
        //saving to the database
        $permission = Permission::find(request()->input('id'));
        $permission->name = request()->input('name');
        $permission->guard_name = request()->input('guard_name') ?? 'web';
        $permission->save();

        return redirect()->route('bustravel.users.permissions')->with(ToastNotification::toast($permission->name.' has successfully been Updated','Permission Updating'));
    }

    //Delete Permission
    public function deletepermissions($id)
    {
        $permission = Permission::find($id);
        $name = $permission->name;
        $permission->delete();
        return Redirect::route('bustravel.users.permissions')->with(ToastNotification::toast($name.' has successfully been deleted','Permission Deleted','error'));
    }

    //fetching Roles
    public function roles()
    {
        $roles = Role::all();

        return view('bustravel::backend.users.roles.index', compact('roles'));
    }

    public function createroles()
    {
        $permissions = Permission::all(); //Get all permissions
        return view('bustravel::backend.users.roles.create', compact('permissions'));
    }

    // save Role
    public function storeroles(Request $request)
    {
        //validation
        //saving to the database
        $role = new Role();
        $role->name = request()->input('name');
        $role->guard_name = request()->input('guard_name') ?? 'web';
        $role->save();
        $role->syncPermissions();
        $permissions = $request->input('permissions') ?? 0;
        if ($permissions != 0) {
            foreach ($permissions as $permission_id) {
                $permission_role = Permission::find($permission_id);
                $role->givePermissionTo($permission_role);
            }
        }
        return redirect()->route('bustravel.users.roles')->with(ToastNotification::toast($role->name.' has successfully been saved','Role Saving'));
    }

    public function editroles($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all();
        if (is_null($role)) {
            return Redirect::route('bustravel.users.roles');
        }

        return view('bustravel::backend.users.roles.edit', compact('role', 'permissions'));
    }

    //Upadte Role
    public function updateroles($id, Request $request)
    {
        //saving to the database
        $role = Role::find($id);
        $role->name = request()->input('name');
        $role->guard_name = request()->input('guard_name') ?? 'web';
        $role->save();
        $role->syncPermissions();
        $permissions = $request->input('permissions') ?? 0;
        if ($permissions != 0) {
            foreach ($permissions as $permission_id) {
                $permission_role = Permission::find($permission_id);
                $role->givePermissionTo($permission_role);
            }
        }
        return redirect()->route('bustravel.users.roles.edit', $id)->with(ToastNotification::toast($role->name.' has successfully been Updated','Role Updating'));
    }

    //Delete Role
    public function deleteroles($id)
    {
        $role = Role::find($id);
        $name = $role->name;
        $role->delete();
        return Redirect::route('bustravel.users.roles')->with(ToastNotification::toast($name.' has successfully been deleted','Role Deleted','error'));
    }

    //fetching Roles
    public function users()
    {
      if(auth()->user()->hasAnyRole('BT Administrator'))
      {
          $users = config('bustravel.user_model', User::class)::where('operator_id',auth()->user()->operator_id)->get();
      }
      else
      {
          $users = config('bustravel.user_model', User::class)::all();
      }
        return view('bustravel::backend.users.users.index', compact('users'));
    }

    public function createusers()
    {
      if(auth()->user()->hasAnyRole('BT Administrator'))
        {
          $operator_roles = GeneralSetting::where('setting_prefix','operator_roles')->first()->setting_value ?? 'BT User';
          $array_roles = explode(',', $operator_roles);
          $arrayrole=[];
          foreach($array_roles as$key=>$array_role)
          {
            $role =Role::where('name','like',"%$array_role%")->first();
            if(!is_null($role))
            {
            $arrayrole[]=$role->id;
            }
          }
          $roles=Role::whereIn('id',$arrayrole)->get();
        }
      else
        {
          $roles = Role::all(); //Get all permissions
        }
        $operators = Operator::where('status', 1)->get();
        $stations = Station::all();
        return view('bustravel::backend.users.users.create', compact('roles', 'operators','stations'));
    }

    // save Role
    public function storeusers(Request $request)
    {
        //validation
        //saving to the database
        $validation = request()->validate([
          'name'                  => 'required|max:255|unique:users',
          'email'                 => 'required|email|max:255|unique:users',
          'password'              => 'required|min:7|confirmed',
          'password_confirmation' => 'required|same:password',
        ]);
        $user_class = config('bustravel.user_model', User::class);
        $user = new $user_class();
        $user->name = request()->input('name');
        $user->email = request()->input('email');
        $user->password = bcrypt(request()->input('password'));
        $user->phone_number = request()->input('phone_number');
        $user->status = request()->input('status');
        $user->operator_id = request()->input('operator_id') ?? 0;
        $user->workstation = request()->input('workstation');
        $user->save();
        $user->assignRole(request()->input('role'));

        return redirect()->route('bustravel.users')->with(ToastNotification::toast($user->name.' has successfully been saved','User Saving'));
    }

    public function editusers($id)
    {
      if(auth()->user()->hasAnyRole('BT Administrator'))
        {
          $operator_roles = GeneralSetting::where('setting_prefix','operator_roles')->first()->setting_value ?? 'BT User';
          $array_roles = explode(',', $operator_roles);
          $arrayrole=[];
          foreach($array_roles as$key=>$array_role)
          {
            $role =Role::where('name','like',"%$array_role%")->first();
            if(!is_null($role))
            {
            $arrayrole[]=$role->id;
            }
          }
          $roles=Role::whereIn('id',$arrayrole)->get();
        }
      else
        {
          $roles = Role::all(); //Get all permissions
        }
        $operators = Operator::where('status', 1)->get();
        $stations = Station::all();
        $user = config('bustravel.user_model', User::class)::find($id);
        if (is_null($user)) {
            return Redirect::route('bustravel.users');
        }

        return view('bustravel::backend.users.users.edit', compact('roles', 'operators', 'user','stations'));
    }

    //Upadte Role
    public function updateusers($id, Request $request)
    {
        //saving to the database
        if (request()->input('password') == '') {
            $validation = request()->validate([
            'name'  => 'required|max:255|unique:users,name,'.$id,
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            //'password' => 'required|min:7|confirmed',
            //'password_confirmation' => 'required|same:password'
          ]);
            $user = config('bustravel.user_model', User::class)::find($id);
            $user->name = request()->input('name');
            $user->email = request()->input('email');
            $user->phone_number = request()->input('phone_number');
            $user->status = request()->input('status');
            $user->operator_id = request()->input('operator_id') ?? 0;
            $user->workstation = request()->input('workstation');
            $user->save();
            $user->syncRoles(request()->input('role'));
        } else {
            $validation = request()->validate([
            'name'                  => 'required|max:255|unique:users,name,'.$id,
            'email'                 => 'required|email|max:255|unique:users,email,'.$id,
            'password'           => 'required|min:7|confirmed',
            'password_confirmation' => 'required|same:password',
          ]);
            $user = config('bustravel.user_model', User::class)::find($id);
            $user->name = request()->input('name');
            $user->email = request()->input('email');
            $user->password = bcrypt(request()->input('password'));
            $user->phone_number = request()->input('phone_number');
            $user->status = request()->input('status');
            $user->operator_id = request()->input('operator_id') ?? 0;
            $user->save();
            $user->syncRoles(request()->input('role'));
        }

        return redirect()->route('bustravel.users.edit', $id)->with(ToastNotification::toast($user->name.' has successfully been Updated','User Updating'));
    }

    //Delete Role
    public function deleteusers($id)
    {
        $user = config('bustravel.user_model', User::class)::find($id);
        $name = $user->name;
        $user->delete();
        return Redirect::route('bustravel.users')->with(ToastNotification::toast($name.' has successfully been deleted','User Deleted','error'));
    }

    public function changepassword()
    {
        return view('bustravel::backend.users.users.changepassword');
    }

    public function changepassword_save()
    {
      $validation = request()->validate([
      'password'           => 'required|min:7|confirmed',
      'password_confirmation' => 'required|same:password',
    ]);
      $id =auth()->user()->id;
      $user = config('bustravel.user_model', User::class)::find($id);
      $user->password = bcrypt(request()->input('password'));
      $user->save();
         return redirect()->route('bustravel.users.changepassword')->with(ToastNotification::toast(' Password successfully changed','Password Changed'));
    }
}
