<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Task;
use App\Http\Requests\CardRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($task_id)
    {
        return Card::whereTaskId($task_id)->latest()->get();
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
            "id" => $card->id,
            'message' => 'Card created!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($task_id, $id)
    {
        $card = Card::whereId($id)->with(['created_by:id,name,email'])->first();
        if (!$card) {
            throw new NotFoundHttpException('No record found!');
        }

        return $card;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CardRequest $request, $card_id, $id)
    {
        $card = Card::find($id);
        if (!$card) {
            throw new NotFoundHttpException('No record found!');
        }
        $card->name = $request->name;
        $card->name = $request->name;
        $updatedCard = $card->update();

        return response()->json([
            "id" => $updatedCard->id,
            'message' => 'Card updated!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($task_id, $id)
    {
        $card = Card::find($id);
        if (!$card) {
            throw new NotFoundHttpException('No record found!');
        }
        $card->delete();
    }
}
