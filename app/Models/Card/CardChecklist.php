<?php

namespace App\Models\Card;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CardChecklist extends Model
{
    protected $fillable = ['card_id', 'name', 'checked', 'created_by'];

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
