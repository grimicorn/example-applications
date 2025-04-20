<?php

namespace App\Support\Listing;

use App\Support\Listing\Documents;
use App\Support\Listing\HasCoverPhotoUrl;

trait HasDocuments
{
    use HasCoverPhotoUrl;

    /**
     * Listing photos document collection.
     *
     * @var string
     */
    protected $photosCollectionKey = 'photos';

    /**
     * Listing files document collection.
     *
     * @var string
     */
    protected $filesCollectionKey = 'files';

    /**
     * Get the listing's documents.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function documents()
    {
        $documents = (new Documents($this))->get();

        if (!$this->previewing) {
            return $documents;
        }

        return collect([
            $this->photosCollectionKey => collect($this->previewPhotos),
            $this->filesCollectionKey => collect($this->previewFiles),
        ]);
    }

    /**
     * Get the listing's documents for display.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function documentsForDisplay()
    {
        return (new Documents($this))->getForDisplay();
    }

    /**
     * Get the listing's photos.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function photos()
    {
        return $this->documents()->get($this->photosCollectionKey);
    }

    /**
     * Get the listing's files.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function files()
    {
        return $this->documents()->get($this->filesCollectionKey);
    }

    /**
     * Get the listing's photos for display.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function photosForDisplay()
    {
        return $this->documentsForDisplay()->get($this->photosCollectionKey);
    }

    /**
     * Get the listing's files for display.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function filesForDisplay()
    {
        return $this->documentsForDisplay()->get($this->filesCollectionKey);
    }

    /**
     * Adds a document to a listing's document media collection.
     *
     * @param string $collection
     * @param string $key
     */
    public function addDocumentFromRequest($collection, $key)
    {
        $this->addMediaFromRequest($key)->toMediaCollection($collection);
    }

    /**
     * Adds a document to a listing's document media collection.
     *
     * @param string $collection
     * @param string $key
     */
    public function addDocument($collection, $path)
    {
        $this->addMedia($path)->toMediaCollection($collection);
    }

    /**
     * Adds a photo to the listing's photo media collection.
     *
     * @param string $collection
     * @param string $path
     */
    public function addPhoto($path)
    {
        $this->addDocument($this->photosCollectionKey, $path);
    }

    /**
    * Adds a file to the listing's file media collection.
    *
    * @param string $collection
    * @param string $path
    */
    public function addFile($path)
    {
        $this->addDocument($this->filesCollectionKey, $path);
    }

    public function getSlides($slideSize = 'photo_featured')
    {
        $photos = $this->photos();

        if ($photos->isEmpty()) {
            $urlProperty = "cover_{$slideSize}_url";
            return r_collect([
                [
                    'url' => $this->$urlProperty,
                    'lightbox_url' => $this->cover_photo_url,
                    'name' => "{$this->title} image",
                ]
            ]);
        }

        return $this->photos()->map(function ($photo) use ($slideSize) {
            return collect([
                'url' => $photo->getFullUrl($slideSize),
                'lightbox_url' => $photo->getFullUrl(),
                'name' => $photo->name,
            ]);
        });
    }
}
