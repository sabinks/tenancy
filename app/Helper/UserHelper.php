<?php

namespace App\Helper;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserHelper
{
    public function donotAllowSelfPermissionAssignment($name)
    {
        $user = User::find(Auth::id());
        $roles = $user->getRoleNames();
        $permissions = [];
        foreach ($roles as $key => $role) {
            $user_role = Role::findByName($role, 'api');
            $permissions = [...$permissions, ...$user_role->permissions->pluck('name')];
        }

        if (in_array($name, $permissions)) {
            if (
                in_array($name, ['assign-permission', 'revoke-permission'])
            ) {
                return true;
            }
        }
        return false;
    }
}
