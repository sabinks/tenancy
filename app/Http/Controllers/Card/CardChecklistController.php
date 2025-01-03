<?php

namespace App\Http\Controllers\Card;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Models\Card\CardChecklist;
use App\Http\Controllers\Controller;
use App\Http\Requests\CardChecklistRequest;
use App\Models\Card\ChecklistItem;
use GuzzleHttp\Exception\ServerException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CardChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($card_id)
    {
        return CardChecklist::whereCardId($card_id)->with(['created_by:id,name,email'])->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CardChecklistRequest $request, $card_id)
    {
        $card = Card::find($card_id);
        $cardChecklist = $card->checklists()->create([
            'name' => $request->name
        ]);
        return response()->json([
            'id' => $cardChecklist->id,
            'message' => 'Card Checklist stored!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($card_id, $id)
    {
        $cardChecklist = CardChecklist::whereId($id)->with(['created_by:id,name,email'])->first();
        if (!$cardChecklist) {
            throw new NotFoundHttpException('No record found');
        }
        return $cardChecklist;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CardChecklistRequest $request, $card_id, $id)
    {
        $cardChecklist = CardChecklist::find($id);
        if (!$cardChecklist) {
            throw new NotFoundHttpException('No record found');
        }
        $cardChecklist->name = $request->name;
        $updatedCardChecklist = $cardChecklist->update();
        if (!$updatedCardChecklist) {
            throw new HttpException(500, "Server Error");
        }
        return response()->json([
            'id' => $cardChecklist->id,
            'message' => 'Card Checklist updated!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($card_id, $id)
    {
        $cardChecklist = CardChecklist::find($id);
        if (!$cardChecklist) {
            throw new NotFoundHttpException('No record found');
        }
        $cardChecklist->checklistItems()->delete();
        $cardChecklist->delete();
    }
}
