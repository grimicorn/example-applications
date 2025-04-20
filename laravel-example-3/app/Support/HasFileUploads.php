<?php

namespace App\Support;

trait HasFileUploads
{
    /**
     * Gets the hashed file name for the media library.
     *
     * @param  string $string
     *
     * @return string
     */
    public function getMediaHashFilename($key)
    {
        $path = request()->file($key)->getClientOriginalName();
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $name = md5($path . time() . uniqid());

        return "{$name}.{$extension}";
    }
}
