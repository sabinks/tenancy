<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Task::whereBoardId($request->board_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $taskCount = Task::whereBoardId($request->board_id)->count();
        $log = Task::create([
            'board_id' => $request->board_id,
            'name' => $request->name,
            'indexing' => $taskCount + 1,
        ]);
        return response()->json([
            'message' => 'Task created!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $taskList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $taskList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $taskList)
    {
        //
    }
}
