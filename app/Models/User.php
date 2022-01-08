<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'type',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function analyses()
    {
        return $this->hasMany(Analysis::class, 'user_id')
            ->withCount([
                'credsLogs',
                'exploitsLogs' => function ($query) {
                    $query->where('status', 'vulnerable');
                },
            ]);
    }

    public function linux_based_analyses()
    {
        return $this->analyses()
            ->orderBy('created_at', 'desc')
            ->where('fuzzing_status', 'n/a')
            ->get();
    }

    public function os_less_analyses()
    {
        return $this->analyses()
            ->orderBy('created_at', 'desc')
            ->where('static_status', 'n/a')
            ->get();
    }
}
