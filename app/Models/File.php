<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamp = ["created_at"];
    const UPDATED_AT = null;

    protected $fillable = [
        'author_id',
        'original_filename',
        'path',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
