<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BoardRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Board::get();
    }

    public function list(Request $request)
    {
        return Board::latest()->get(['id', 'title']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BoardRequest $request)
    {
        $input =  $request->only(['title', 'visibility', 'workspace_id']);

        DB::beginTransaction();
        try {
            $board = Board::create([
                'title' => $input['title'],
                'visibility' => $input['visibility'],
                'workspace_id' => $input['workspace_id'],
                'background' => 'template_01', //to be later decided
                'publish' => true
            ]);
            $board->tasks()->createMany([
                ['name' => 'Backlog', 'indexing' => 1, 'created_by' => Auth::id()],
                ['name' => 'To do', 'indexing' => 2, 'created_by' => Auth::id()],
                ['name' => 'Doing', 'indexing' => 3, 'created_by' => Auth::id()],
                ['name' => 'Done', 'indexing' => 4, 'created_by' => Auth::id()],
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Board created!',
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        if (!$board) {
            throw new NotFoundHttpException('No record found!');
        }
        return [
            'board' => $board,
            'tasks' => $board->task,
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        $board->tasks()->delete();
        $board->tasks()->cards()->delete();
        $board->delete();
    }
}
