<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'analysis_uuid',
        'path',
    ];

    public function staticLogs()
    {
        return $this->hasMany(StaticLog::class);
    }
}
