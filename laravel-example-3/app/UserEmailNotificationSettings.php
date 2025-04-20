<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class UserEmailNotificationSettings extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enable_all',
        'enable_due_diligence',
        'enable_login',
        'due_diligence_digest',
        'blog_posts',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enable_all' => 'boolean',
        'enable_due_diligence' => 'boolean',
        'enable_login' => 'boolean',
        'due_diligence_digest' => 'boolean',
        'blog_posts' => 'boolean',
    ];

    /**
     * The attribute defaults.
     *
     * @var array
     */
    protected $attributes = [
        'enable_all' => true,
        'blog_posts' => true,
    ];

    /**
     * Get the user that owns the settings.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
