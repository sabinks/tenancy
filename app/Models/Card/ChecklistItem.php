<?php

namespace App\Models\Card;

use App\Models\Card\CardChecklist;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    protected $table = 'checklist_items';

    protected $fillable = ['card_checklist_id', 'checked', 'name', 'assigned_date', 'assigned_to'];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'datetime:Y-m-d H:i:s',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }
    public function cardChecklist()
    {
        return $this->belongsTo(CardChecklist::class, 'card_checklist_id');
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
