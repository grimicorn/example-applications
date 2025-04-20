<?php

namespace App\Domain\Supports;

class Location
{
    protected $latitude;
    protected $longitude;

    public function __construct($longitude, $latitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function latitude()
    {
        return $this->latitude;
    }

    public function longitude()
    {
        return $this->longitude;
    }
}
