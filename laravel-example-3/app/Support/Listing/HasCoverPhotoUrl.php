<?php

namespace App\Support\Listing;

use App\BusinessCategory;

trait HasCoverPhotoUrl
{
    /**
     * Get the listing's cover photo URL.
     *
     * @param  string  $value
     * @return string
     */
    public function getCoverPhotoAttribute()
    {
        $photo = optional($this->photos())->first();

        return $photo ? $photo : null;
    }

    /**
     * Gets the photo URL.
     *
     * @param  string $conversion
     *
     * @return string|null
     */
    public function getCoverPhotoUrl($conversion = '')
    {
        $photo = $this->cover_photo;

        return $photo ? $photo->getFullUrl($conversion) : $this->getFallback($conversion);
    }

    /**
     * Gets the fallback photo URL
     *
     * @param string $conversion
     * @return void
     */
    protected function getFallback($conversion)
    {
        $filename = $this->getFileName($conversion);

        return asset("/img/defaults/listing/{$filename}");
    }

    protected function getFileName($conversion = '')
    {
        $default = "{$conversion}.png";

        if (!$this->business_categories) {
            return $default;
        }

        $slug = $this->business_categories
        ->pluck('slug')->implode('-');

        return $slug ? "{$slug}.png" : $default;
    }

    /**
     * Gets the listings's photo_upload url.
     *
     * @return string
     */
    protected function getCoverPhotoUploadUrlAttribute()
    {
        return $this->getCoverPhotoUrl('photo_upload');
    }

    /**
     * Gets the listings's photo_featured url.
     *
     * @return string
     */
    protected function getCoverPhotoFeaturedUrlAttribute()
    {
        return $this->getCoverPhotoUrl('photo_featured');
    }

    /**
     * Gets the listings's photo_favorite_thumbnail url.
     *
     * @return string
     */
    protected function getCoverPhotoFavoriteThumbnailUrlAttribute()
    {
        return $this->getCoverPhotoUrl('photo_favorite_thumbnail');
    }

    /**
     * Gets the listings's photo_roll url.
     *
     * @return string
     */
    protected function getCoverPhotoRollUrlAttribute()
    {
        return $this->getCoverPhotoUrl('photo_roll');
    }

    /**
     * Gets the user's cover_photo_url.
     *
     * @return string
     */
    protected function getCoverPhotoUrlAttribute()
    {
        return $this->getCoverPhotoUrl();
    }

    public function hasCoverPhoto()
    {
        return !$this->photos()->isEmpty();
    }
}
