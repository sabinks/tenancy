<?php

namespace App\Http\Controllers;

use App\Helper\UserHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RolePermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:Superadmin', only: [
                'assignPermission',
            ]),
            new Middleware('role:Superadmin', only: [
                'revokePermission',
            ]),
            new Middleware('role:Superadmin', only: [
                'rolePermissions'
            ]),
        ];
    }

    public function assignPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'permission_id' => 'required',
        ], [
            'permission_id.required' => 'Please select user',
            'role_id.required' => 'Please select role',
        ]);
        if ($validator->fails()) {

            return response($validator->errors(), 422);
        }
        $input = $request->only(['permission_id', 'role_id']);
        DB::beginTransaction();
        try {
            $role = Role::find($input['role_id']);
            $permission = Permission::find($input['permission_id']);
            // $userHelper = new UserHelper();
            // $status = $userHelper->donotAllowSelfPermissionAssignment($permission->name);
            // if ($status) {
            //     return response()->json([
            //         'message' => 'Permission denied!'
            //     ], 403);
            // }

            $result = $role->givePermissionTo($permission);
            DB::commit();

            return response()->json([
                'message' => 'Permission assigned to role!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }

    public function revokePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'permission_id' => 'required',
        ], [
            'permission_id.required' => 'Please select user',
            'role_id.required' => 'Please select role',
        ]);
        if ($validator->fails()) {

            return response($validator->errors(), 422);
        }
        $input = $request->only(['permission_id', 'role_id']);
        DB::beginTransaction();
        try {
            $role = Role::find($input['role_id']);
            $permission = Permission::find($input['permission_id']);
            $result = $role->revokePermissionTo($permission->name);
            DB::commit();

            return response()->json([
                'message' => 'Permission revoked!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }

    public function rolePermissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
        ], [
            'role_id.required' => 'Please select role',
        ]);
        if ($validator->fails()) {

            return response($validator->errors(), 422);
        }
        $input = $request->only(['role_id']);
        DB::beginTransaction();
        try {
            $role = Role::find($input['role_id']);
            if (!$role) {
                return response()->json([
                    'message' => 'Role not found!',
                ], 404);
            }
            $permissions = $role->permissions;

            return $permissions;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}
