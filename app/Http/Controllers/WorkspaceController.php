<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkspaceRequest;
use App\Models\Workspace;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paginate = $request->pagination;
        $order_by = $request->order_by;
        $order = $request->order;
        return Workspace::orderBy($order_by, $order)->paginate($paginate);
    }

    public function list(Request $request)
    {
        return Workspace::get(['id', 'title']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkspaceRequest $request)
    {
        $title = $request->title;
        $workspace = Workspace::create([
            'title' => $title
        ]);
        if (!$workspace) {
            throw new Exception("Error Occured", 400);
        }
        return response()->json([
            'message' => 'Workspace created!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspace $workspace)
    {
        if (!$workspace) {
            throw new NotFoundHttpException('Workspace not found!');
        }
        return $workspace;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkspaceRequest $request, Workspace $workspace)
    {
        if (!$workspace) {
            throw new NotFoundHttpException('Workspace not found!');
        }
        $workspace['title'] = $request->title;
        $workspace->update();

        return response()->json([
            'message' => 'Workspace updated!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace)
    {
        if (!$workspace) {
            throw new NotFoundHttpException('Workspace not found!');
        }
        $workspace->delete();

        return response()->json([
            'message' => 'Workspace deleted!',
        ], 200);
    }
}
