<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Task::whereBoardId($request->board_id)->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $taskCount = Task::whereBoardId($request->board_id)->count();
        $task = Task::create([
            'board_id' => $request->board_id,
            'name' => $request->name,
            'indexing' => $taskCount + 1,
        ]);
        return response()->json([
            'id' => $task->id,
            'message' => 'Task created!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if (!$task) {
            throw new NotFoundHttpException('No record found!');
        }
        return $task;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if (!$task) {
            throw new NotFoundHttpException('No record found!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (!$task) {
            throw new NotFoundHttpException('No record found!');
        }
        $task->delete();
    }
}
