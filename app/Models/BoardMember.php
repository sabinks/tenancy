<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardMember extends Model
{
    protected $fillable = [
        'board_id',
        'member_id'
    ];
}
