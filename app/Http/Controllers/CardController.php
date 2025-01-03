<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\CardRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($task_id)
    {
        return Card::whereTaskId($task_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CardRequest $request, $task_id)
    {
        $cardCount = Card::whereTaskId($task_id)->count();
        $task = Task::find($task_id);
        $card = $task->cards()->create([
            'board_id' => $task->board->id,
            'name' => $request->name,
            'description' => '',
            'indexing' => $cardCount + 1,
        ]);
        return response()->json([
            'message' => 'Card created!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $card = Card::find($id);
        if (!$card) {
            return response()->json([
                'message' => 'Card not found!',
            ], 404);
        }
        return $card;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        //
    }
}
