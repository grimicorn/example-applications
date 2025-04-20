<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getNameAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $value = parse_url($this->url, PHP_URL_HOST);
        $value = ltrim($value, 'www.');

        return $value;
    }
}
