<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function assignPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required',
            'role_id' => 'required',
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
            $result = $role->givePermissionTo($permission->name);
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
            'permission_id' => 'required',
            'role_id' => 'required',
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
