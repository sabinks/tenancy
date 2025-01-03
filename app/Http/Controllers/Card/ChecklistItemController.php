<?php

namespace App\Http\Controllers\Card;

use Illuminate\Http\Request;
use App\Models\Card\ChecklistItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChecklistItemCheckedRequest;
use App\Http\Requests\ChecklistItemRequest;
use App\Http\Requests\UpdateChecklistItemRequest;
use App\Models\Card\CardChecklist;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChecklistItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($checklist_id)
    {
        return ChecklistItem::where('card_checklist_id', $checklist_id)->with(['assignedTo'])->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChecklistItemRequest $request, $checklist_id)
    {
        $checklistItem = CardChecklist::find($checklist_id);
        $savedChecklistItem = $checklistItem->checklistItems()->create([
            'name' => $request->name,
            'assigned_to' => $request->assigned_to ?: null,
            'assigned_date' => $request->assigned_date ?: null
        ]);
        return response()->json([
            'id' => $savedChecklistItem->id,
            'message' => 'Checklist Item added!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($checklist_id, $id)
    {
        $checklistItem = ChecklistItem::where('id', $id)->with(['assignedTo:id,name,email'])->first();
        if (!$checklistItem) {
            throw new NotFoundHttpException('No record found');
        }
        return $checklistItem;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChecklistItemRequest $request, $checklist_id, $id)
    {
        $checklistItem = ChecklistItem::find($id);
        if (!$checklistItem) {
            throw new NotFoundHttpException('No record found');
        }
        $checklistItem->name = $request->name ?: $checklistItem->name;
        $checklistItem->assigned_to =  $request->assigned_to ?: $checklistItem->assigned_to;
        $checklistItem->assigned_date =  $request->assigned_date ?: $checklistItem->assigned_date;
        $updatedChecklistItem = $checklistItem->update();
        if (!$updatedChecklistItem) {
            throw new HttpException(500, "Server Error");
        }
        return response()->json([
            'id' => $checklistItem->id,
            'message' => 'Checklist item updated!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($checklist_id, $id)
    {
        $checklistItem = ChecklistItem::find($id);
        if (!$checklistItem) {
            throw new NotFoundHttpException('No record found');
        }
        $checklistItem->delete();
    }

    public function checklistItemChecked(ChecklistItemCheckedRequest $request, $id)
    {
        $checklistItem = CheckListItem::find($id);
        if (!$checklistItem) {
            throw new NotFoundHttpException('No record found');
        }
        $checklistItem->checked = $request->checked;
        $checklistItem->update();
    }
}
