<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'google_access_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'google_access_token' => 'json',
        'default_due_date_enabled' => 'boolean',
    ];

    /**
     * Scope a query to only include user with a specified email
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $email
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', 'dtholloran@gmail.com');
    }

    /**
     * Get users request log.
     */
    public function requestLog()
    {
        return $this->hasMany('App\RequestLog');
    }
}
