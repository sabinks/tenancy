<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    protected $fillable = ['title', 'data'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->user_id = Auth::id();
        });
    }
}
