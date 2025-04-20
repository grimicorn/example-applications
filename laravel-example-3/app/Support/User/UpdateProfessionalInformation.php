<?php

namespace App\Support\User;

use App\Support\HasForms;
use Illuminate\Http\Request;
use App\Support\PreviewMedia;
use App\Support\HasFileUploads;
use Illuminate\Support\Facades\Auth;

class UpdateProfessionalInformation
{
    protected $user;
    protected $request;
    protected $information;
    protected $preview;

    use HasForms, HasFileUploads;

    public function __construct(Request $request, $preview = false)
    {
        $this->user = Auth::user();
        $this->request = $request;
        $this->preview = $preview;
        $this->information = $this->user->professionalInformation;
    }

    /**
     * Update the users professional information.
     *
     * @return \App\UserProfessionalInformation
     */
    public function update()
    {
        // Get the fields.
        $fields = $this->fields();

        // Massage the fields before saving.
        $fields = $this->filterSubmittedArray('links', $fields);
        $fields = $this->convertTagsToArray($fields, ['other_designations']);
        $fields = $this->setAreasServed($fields);
        $fields = $this->handleFiles($fields);

        // Update the user
        if ($this->preview) {
            if (request()->has('company_logo_url_file')) {
                $fields['company_logo'] = $fields['company_logo'] ?? null;
                $this->information->preview_company_logo = $fields['company_logo'] ?? null;
                $this->information->company_logo_id = $fields['company_logo'] ? $this->information->company_logo_id : null;
                unset($fields['company_logo']);
            }

            if ((bool) request()->get('company_logo_url_delete')) {
                $this->information->preview_company_logo = null;
                $this->information->company_logo_id = null;
            }

            $this->information->fill($fields);
        } else {
            $this->information->update($fields);
        }

        return $this->information;
    }

    /**
     * Handle the uploaded files
     *
     * @param array $fields
     *
     * @return array
     */
    protected function handleFiles($fields)
    {
        // We only want to remove/upload photos if it is a preview.
        // We will need to handle the photos a little differently in preview.
        if (!$this->preview) {
            $fields = $this->removeOldLogo($fields);
            $fields = $this->uploadNewLogo($fields);

            return $fields;
        }

        // User Photo will be a preview if it exists.
        $logo = $fields['company_logo_url_file'] ?? null;
        if (!is_null($logo)) {
            $fields['company_logo'] = new PreviewMedia($logo);
        }

        return $fields;
    }

    /**
     * Upload a new logo if needed.
     *
     * @param  array $fields
     *
     * @return array
     */
    protected function uploadNewLogo($fields)
    {
        if (!request()->has('company_logo_url_file')) {
            return $fields;
        }

        $media = $this->information
        ->addMediaFromRequest('company_logo_url_file')
        ->usingFileName($this->getMediaHashFilename('company_logo_url_file'))
        ->toMediaCollection('company_logo');

        $fields['company_logo_id'] = $media->id;
        unset($fields['company_logo_url_file']);

        return $fields;
    }

    /**
     * Remove the old logo if deleted.
     *
     * @param  array $fields
     *
     * @return array
     */
    protected function removeOldLogo($fields)
    {
        // Make sure we want to remove the logo.
        if (!isset($fields['company_logo_url_delete']) or !$fields['company_logo_url_delete']) {
            return $fields;
        }

        // And we have a logo to delete.
        if ($this->information->company_logo) {
            $this->information->company_logo->delete();
            $fields['company_logo_id'] = null;
        }

        unset($fields['company_logo_url_delete']);

        return $fields;
    }

    /**
     * Get the submitted fields.
     *
     * @return array
     */
    protected function fields()
    {
        $fields = $this->request->only([
            'company_logo_url_delete',
            'company_logo_url_file'
        ]);

        if (!isset($this->request->all()['professionalInformation'])) {
            return $fields;
        }

        return collect($this->request->all()['professionalInformation'])
                     ->only($this->keys())
                     ->merge($fields)
                     ->toArray();
    }

    /**
     * Set the areas served.
     *
    * @param array $fields
     *
     * @return array
     */
    protected function setAreasServed($fields)
    {
        if (isset($fields['areas_served'])) {
            $fields['areas_served'] = array_values($fields['areas_served']);
        }

        return $fields;
    }

    /**
     * Get the user keys.
     *
     * @return array
     */
    protected function keys()
    {
        return $this->information->getFillable();
    }
}
