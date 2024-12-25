<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:assign-role', only: ['assignRole']),
            new Middleware('permission:revoke-role', only: ['revokeRole']),
        ];
    }

    public function getUser()
    {
        $user_id = Auth::user()->id;
        $user = User::whereId($user_id)->first();
        $response['name'] = $user->name;
        $response['email'] = $user->email;
        $roles = $user->getRoleNames();
        $response['roles'] = $roles;
        $permissions = [];
        foreach ($roles as $key => $role) {
            $user_role = Role::findByName($role, 'api');
            $permissions = [...$permissions, ...$user_role->permissions->pluck('name')];
        }
        $response['permissions'] =  $permissions;
        return $response;
    }

    public function assignRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'role_id' => 'required',
        ], [
            'user_id.required' => 'Please select user',
            'role_id.required' => 'Please select role',
        ]);
        if ($validator->fails()) {

            return response($validator->errors(), 422);
        }
        $input = $request->only(['user_id', 'role_id']);
        DB::beginTransaction();
        try {
            $user = User::find($input['user_id']);
            $role = Role::find($input['role_id']);
            $user->assignRole($role->name);
            DB::commit();

            return response()->json([
                'message' => 'Role assigned to user!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }

    public function revokeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'role_id' => 'required',
        ], [
            'user_id.required' => 'Please select user',
            'role_id.required' => 'Please select role',
        ]);
        if ($validator->fails()) {

            return response($validator->errors(), 422);
        }
        $input = $request->only(['user_id', 'role_id']);
        DB::beginTransaction();
        try {
            $user = User::find($input['user_id']);
            $role = Role::find($input['role_id']);
            $user->removeRole($role->name);
            DB::commit();

            return response()->json([
                'message' => 'Role assigned to user!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }

    public function userRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => 'Please select role',
        ]);
        if ($validator->fails()) {

            return response($validator->errors(), 422);
        }
        $input = $request->only(['user_id']);
        DB::beginTransaction();
        try {
            $user = User::find($input['user_id']);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found!',
                ], 404);
            }
            $roles = $user->roles;

            return $roles;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}
