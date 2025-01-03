<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use App\Models\Card\CardLabel;
use App\Models\Card\CardChecklist;
use App\Models\Card\CardAttachment;
use App\Models\Card\CardFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'board_id',
        'task_id',
        'name',
        'description',
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
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    public function members()
    {
        return $this->belongsToMany(User::class, 'card_members', 'card_id', 'member_id');
    }
    public function labels()
    {
        return $this->hasMany(CardLabel::class);
    }
    public function checklists()
    {
        return $this->hasMany(CardChecklist::class);
    }
    public function attachments()
    {
        return $this->hasMany(CardAttachment::class);
    }
    public function files()
    {
        return $this->hasMany(CardFile::class);
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
