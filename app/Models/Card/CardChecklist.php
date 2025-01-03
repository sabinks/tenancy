<?php

namespace App\Models\Card;

use App\Models\User;
use App\Models\Card\ChecklistItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CardChecklist extends Model
{
    protected $fillable = ['card_id', 'name', 'created_by'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }
}
