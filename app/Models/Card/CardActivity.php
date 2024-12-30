<?php

namespace App\Models\Card;

use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CardActivity extends Model
{
    protected $fillable = [
        'card_id',
        'comment',
        'created_by'
    ];
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });
        self::updating(function ($model) {
            $model->created_by = Auth::id();
        });
    }
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
