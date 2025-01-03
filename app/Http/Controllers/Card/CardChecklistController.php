<?php

namespace App\Http\Controllers\Card;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Models\Card\CardChecklist;
use App\Http\Controllers\Controller;
use App\Http\Requests\CardChecklistRequest;
use App\Models\Card\ChecklistItem;

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
        $card->checklists()->create([
            'name' => $request->name
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($card_id, $id)
    {
        $cardChecklist = CardChecklist::find($id)->with(['created_by:id,name,email'])->first();
        return $cardChecklist;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CardChecklistRequest $request, $card_id, $id)
    {
        $cardChecklist = CardChecklist::find($id);
        $cardChecklist->name = $request->name;
        $cardChecklist->update();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($card_id, $id)
    {
        $cardChecklist = CardChecklist::find($id);
        $cardChecklist->delete();
    }
}
