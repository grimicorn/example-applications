<?php

namespace App\Support\User;

use Spatie\MediaLibrary\Conversion\ConversionCollection;

trait HasPhotoUrl
{
    /**
     * Gets the photo URL.
     *
     * @param  string $conversion
     *
     * @return string|null
     */
    public function getPhotoUrl($conversion = '')
    {
        $photo = is_null($this->preview_photo) ? $this->photo : $this->preview_photo;

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
        return;
    }

    /**
     * Gets the user's photo_url_upload.
     *
     * @return string
     */
    protected function getPhotoUploadUrlAttribute()
    {
        return $this->getPhotoUrl('upload');
    }

    /**
     * Gets the user's photo_url_listing_seller.
     *
     * @return string
     */
    protected function getPhotoListingSellerUrlAttribute()
    {
        return $this->getPhotoUrl('listing_seller');
    }

    /**
     * Gets the user's photo_url_roll.
     *
     * @return string
     */
    protected function getPhotoRollUrlAttribute()
    {
        return $this->getPhotoUrl('roll');
    }

    /**
     * Gets the user's photo_url_featured.
     *
     * @return string
     */
    protected function getPhotoFeaturedUrlAttribute()
    {
        return $this->getPhotoUrl('featured');
    }

    /**
     * Gets the user's thumbnail_small.
     *
     * @return string
     */
    protected function getPhotoThumbnailSmallUrlAttribute()
    {
        return $this->getPhotoUrl('thumbnail_small');
    }

    public function getAllPhotoUrls()
    {
        if (!$this->photo) {
            return collect([]);
        }

        return ConversionCollection::createForMedia($this->photo)->mapWithKeys(function ($conversion) {
            return [
                $conversion->getName() => collect([
                    'src' => $this->getPhotoUrl($conversion->getName()),
                    'width' => $conversion->getManipulations()->getManipulationArgument('width'),
                    'height' => $conversion->getManipulations()->getManipulationArgument('height'),
                ])
            ];
        });
    }

    /**
     * Gets the user's photo_url.
     *
     * @return string
     */
    protected function getPhotoUrlAttribute()
    {
        return $this->getPhotoUrl();
    }

    /**
     * Gets the user's photo.
     *
     * @return string
     */
    protected function getPhotoAttribute()
    {
        if (is_null($this->photo_id)) {
            return;
        }

        return $this->getMedia('photo_url')->where('id', $this->photo_id)->first();
    }
}
