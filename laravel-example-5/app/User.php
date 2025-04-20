<?php

namespace App;

use App\Site;
use Zttp\Zttp;
use App\Concerns\Notifiable;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use Notifiable,
        HasMediaTrait,
        LogsActivity,
        LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    protected static $logFillable = true;

    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
    * The accessors to append to the model's array form.
    *
    * @var array
    */
    protected $appends = ['is_admin', 'name', 'avatar'];

    /**
    * The relationships to append to the model's array form.
    *
    * @var array
    */
    protected $with = ['sites'];

    public function getNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function isAdmin()
    {
        return collect(config('srcwatch.admins'))->contains($this->email);
    }

    public function isSubscriber()
    {
        return !$this->isAdmin();
    }

    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }

    public function getAvatar()
    {
        if ($avatar = $this->getFirstMedia('avatar')) {
            return $avatar->getFullUrl();
        }

        $gravatar = $this->getGravatar();
        if ($gravatar) {
            return $gravatar;
        }

        return $this->getDefaultAvatar();
    }

    public function getDefaultAvatar()
    {
        return \Avatar::create($this->name)->toBase64()->encoded;
    }

    protected function getGravatar()
    {
        $gravatarId = md5(strtolower(trim($this->email)));

        return Cache::remember("user.{$gravatarId}", 24 * 60 * 7, function () use ($gravatarId) {
            try {
                $gravatar = "https://www.gravatar.com/avatar/{$gravatarId}";
                $status = Zttp::get("{$gravatar}?d=404")->status();

                if ($status == 200) {
                    return $gravatar;
                }
            } catch (\Exception $e) {
            }
        });
    }

    protected function getAvatarAttribute()
    {
        return $this->getAvatar();
    }

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile();
    }
}
