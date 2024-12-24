<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $order_by = $request->has('order_by') ? $request->input('order_by') : 'name';
        $order = $request->has('order') ?  $request->input('order') : 'asc';
        $pagination = $request->has('pagination') ? $request->input('pagination') : 10;
        $query = Role::query()->where('name', '<>', 'Super Admin');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
            $query = $query->orderBy('name', $order)->paginate($pagination);
        } else if ($order_by) {
            $query = $query->orderBy($order_by, $order)->paginate($pagination);
        } else {
            $query = $query->orderBy('name', $order)->paginate($pagination);
        }
        return $query;
    }

    public function roleList()
    {
        $query = Role::query()->where('name', '<>', 'Super Admin')->get(['id', 'name'])->toArray();
        return $query;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
        ]);
        if ($validator->fails()) {

            return response($validator->errors(), 422);
        }
        $input = $request->only(['name']);
        // $input['created_by'] = Auth::user()->id;
        // $input['updated_by'] = Auth::user()->id;
        DB::beginTransaction();
        try {
            Role::create($input);
            DB::commit();

            return response()->json([
                'message' => 'New role created!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
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

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {

            return response($validator->errors(), 422);
        }
        $role = Role::find($id);
        if (!$role) {
            return response()->json([
                'message' => 'Role not found!',
            ], 404);
        }
        $data = $request->only(['name']);
        foreach ($data as $key => $value) {
            $role[$key] = $value ? $value : $role[$key];
        }
        // $input['created_by'] = Auth::user()->id;
        // $input['updated_by'] = Auth::user()->id;
        DB::beginTransaction();
        try {
            $role->update();
            DB::commit();

            return response()->json([
                'message' => 'Role updated!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {

            return response()->json([
                'message' => 'Role not found!',
            ], 404);
        }
        if ($role->in_built) {

            return response()->json([
                'message' => 'System role(s) cannot delete!',
            ], 403);
        }
        $model_has_role = DB::table('model_has_roles')->whereRoleId($role->id)->first();

        if ($model_has_role) {

            return response()->json([
                'message' => 'Role used, cannot delete!',
            ], 403);
        }
        DB::beginTransaction();
        try {
            $role->delete();
            DB::commit();

            return response()->json([
                'message' => 'Role deleted!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}
