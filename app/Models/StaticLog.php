<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'line_number',
        'callstack',
        'severity',
        'message',
        'warning_type',
        'code',
    ];
}
