<?php

namespace App\Models\Card;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CardLabel extends Model
{
    protected $fillable = [
        'card_id',
        'base_color',
        'text_color',
        'symbol',
        'name',
        'checked',
        'created_by'
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
