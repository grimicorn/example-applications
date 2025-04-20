<?php

namespace App\Support\User;

trait HasCompanyLogoUrl
{
    /**
     * Get logo URL
     *
     * @param  string $conversion
     *
     * @return string|null
     */
    protected function getLogoUrl($conversion = '')
    {
        $logo = is_null($this->preview_company_logo) ? $this->company_logo : $this->preview_company_logo;

        return $logo ? $logo->getFullUrl($conversion) : null;
    }

    /**
     * Gets the user's company_logo_url.
     *
     * @return string
     */
    protected function getCompanyLogoUrlAttribute()
    {
        return $this->getLogoUrl();
    }

    /**
     * Gets the user's company_logo_upload_url.
     *
     * @return string
     */
    protected function getCompanyLogoUploadUrlAttribute()
    {
        return $this->getLogoUrl('upload');
    }

    /**
     * Gets the user's company_logo_thumbnail_url.
     *
     * @return string
     */
    protected function getCompanyLogoThumbnailUrlAttribute()
    {
        return $this->getLogoUrl('thumbnail');
    }

    /**
     * Gets the user's company_logo.
     *
     * @return string
     */
    protected function getCompanyLogoAttribute()
    {
        if (is_null($this->company_logo_id)) {
            return;
        }

        return $this->getMedia('company_logo')->where('id', $this->company_logo_id)->first();
    }
}
