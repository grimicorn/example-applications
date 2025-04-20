<?php

namespace App;

use App\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BusinessCategory extends BaseModel
{
    use Cachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'slug',
        'parent_id',
    ];

    /**
    * Set the business category slug.
    *
    * @param  string  $value
    * @return void
    */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_slug($value);
    }
}
