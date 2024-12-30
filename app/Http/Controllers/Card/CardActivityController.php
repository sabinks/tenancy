<?php

namespace App\Http\Controllers\Card;

use App\Models\Card\CardActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Card\CardActivityRequest;

class CardActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($card_id)
    {
        return CardActivity::whereCardId($card_id)->with('created_by:id,name,email')->latest()->get()->toArray();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CardActivityRequest $request, $card_id)
    {
        $activity = CardActivity::create([
            'card_id' => $card_id,
            'comment' => $request->comment
        ]);
        return response()->json([
            'message' => 'Activity added!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($card_id, $id)
    {
        $cardActivity = CardActivity::find($id);
        if (!$cardActivity) {
            return response()->json([
                'message' => 'Activity not found!',
            ], 404);
        }
        return $cardActivity;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CardActivityRequest $request, $card_id, $id)
    {
        $cardActivity = CardActivity::find($id);
        if (!$cardActivity) {
            return response()->json([
                'message' => 'Activity not found!',
            ], 404);
        }
        $cardActivity->comment = $request->comment;
        $cardActivity->update();

        return response()->json([
            'message' => 'Card activity updated!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($card_id, $id)
    {
        $cardActivity = CardActivity::find($id);
        if (!$cardActivity) {
            return response()->json([
                'message' => 'Activity not found!',
            ], 404);
        }
        $cardActivity->delete();
        return response()->json([
            'message' => 'Activity deleted!',
        ], 200);
    }
}
