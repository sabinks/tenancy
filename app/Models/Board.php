<?php

namespace App\Models;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = [
        'workspace_id',
        'title',
        'visibility',
        'background',
        'publish',
        'created_by'
    ];
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    public function members()
    {
        return $this->belongsToMany(User::class, 'board_members', 'board_id', 'member_id');
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
