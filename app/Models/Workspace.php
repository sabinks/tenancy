<?php

namespace App\Models;

use App\Models\User;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $fillable = [
        'title',
        'created_by'
    ];
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }
    public function boards()
    {
        return $this->hasMany(Board::class);
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
