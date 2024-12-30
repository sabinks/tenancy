<?php

namespace App\Models;

use App\Models\User;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'board_id',
        'name',
        'indexing',
        'created_by'
    ];
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
