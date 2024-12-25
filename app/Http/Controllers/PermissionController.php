<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('role:Superadmin|Admin', only: ['index']),
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::orderBy('category_name', 'asc')
            ->orderBy('method_name', 'asc')
            ->get(['id', 'name', 'category_name', 'method_name'])->toArray();
        $permissions_categories = Permission::select('category_name')
            ->groupBy('category_name')->orderBy('category_name', 'asc')->get()->pluck('category_name')->toArray();
        return response()->json([
            'permissions' => $permissions,
            'permissions_categories' => $permissions_categories
        ], 201);
    }
}
