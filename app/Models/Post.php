<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'data'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->user_id = Auth::id();
        });

        // self::addGlobalScope(function (Builder $builder) {
        //     $builder->withTrashed();
        // });
    }
}
