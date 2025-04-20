<?php

namespace App\Support\User;

use App\Support\HasForms;
use Illuminate\Http\Request;
use App\Support\PreviewMedia;
use App\Support\HasFileUploads;
use Illuminate\Support\Facades\Auth;

class UpdateBasicDetails
{
    protected $user;
    protected $request;
    protected $preview;

    use HasForms, HasFileUploads;

    public function __construct(Request $request, $preview = false)
    {
        $this->user = Auth::user();
        $this->request = $request;
        $this->preview = $preview;
    }

    /**
     * Update the users basic details.
     *
     * @return User
     */
    public function update()
    {
        // Massage the fields before saving.
        $fields = $this->request->only($this->keys());
        $fields = $this->flipPrimaryRoles($fields);
        $fields = $this->handleFiles($fields);
        // Cleanup a few preview specific things.
        if ($this->preview) {
            $this->user->preview = true;

            if (request()->has('photo_url_file')) {
                $fields['photo'] = $fields['photo'] ?? null;
                $this->user->preview_photo = $fields['photo'];
                $this->user->photo_id = is_null($fields['photo']) ? null : $this->user->photo_id;
                unset($fields['photo']);
            }

            if ((bool) request()->get('photo_url_delete')) {
                $this->user->preview_photo = false;
                $this->user->photo_id = null;
            }
        }

        // Update the user attributes.
        $this->user->fill($fields);

        // We only want to save the user if its not a preview.
        if (!$this->preview) {
            $this->user->save();
        }

        return $this->user;
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
            $fields = $this->removeOldPhoto($fields);
            $fields = $this->uploadNewPhoto($fields);

            return $fields;
        }

        // User Photo will be a preview if it exists.
        $photo = $this->request->file('photo_url_file');
        if (!is_null($photo)) {
            $fields['photo'] = new PreviewMedia($photo);
        }

        return $fields;
    }

    /**
     * Upload a new photo URL if needed.
     *
     * @param  array $fields
     *
     * @return array
     */
    protected function uploadNewPhoto($fields)
    {
        if (!request()->has('photo_url_file')) {
            return $fields;
        }

        $media = $this->user
        ->addMediaFromRequest('photo_url_file')
        ->usingFileName($this->getMediaHashFilename('photo_url_file'))
        ->toMediaCollection('photo_url');

        $fields['photo_id'] = $media->id;
        unset($fields['photo_url_file']);

        return $fields;
    }

    /**
     * Remove the old photo URL if deleted.
     *
     * @param  array $fields
     *
     * @return array
     */
    protected function removeOldPhoto($fields)
    {
        // Make sure we want to remove the photo.
        if (!isset($fields['photo_url_delete']) or !$fields['photo_url_delete']) {
            return $fields;
        }

        // And we have a photo to delete.
        if ($this->user->photo) {
            $this->user->photo->delete();
            $fields['photo_id'] = null;
        }

        unset($fields['photo_url_delete']);

        return $fields;
    }

    /**
     * Flip primary roles to store them correctly.
     *
     * @param array $fields
     *
     * @return array
     */
    protected function flipPrimaryRoles($fields)
    {
        if (!isset($fields['primary_roles'])) {
            $fields['primary_roles'] = [];

            return $fields;
        }

        if (isset($fields['primary_roles'])) {
            $fields['primary_roles'] = $this->flipCheckboxOptions(
                collect($fields['primary_roles'])->filter()
            );
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
        return array_merge($this->user->getFillable(), [
            'photo_url_delete',
            'photo_url_file',
        ]);
    }
}
