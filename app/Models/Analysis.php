<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Analysis extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = "analyses";
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'user_id',
        'device_id',
        'status',
        'exploits_time',
        'creds_time',
    ];

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function exploitsLogs()
    {
        return $this->hasMany(ExploitsLog::class);
    }

    public function credsLogs()
    {
        return $this->hasMany(CredsLog::class);
    }
}
