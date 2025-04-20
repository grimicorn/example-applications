<?php

namespace App\Support\Listing;

use App\Listing;
use App\Support\HasForms;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Media;

class Documents
{
    use HasForms;

    protected $listing;
    protected $collections = ['photos', 'files'];

    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
    }

    /**
     * Updates the documents.
     *
     * @param  Request $request
     *
     * @return Listing
     */
    public function update(Request $request)
    {
        $fields = $this->getFields($request);

        foreach ($this->collections as $collection) {
            if (!is_null($fields[ $collection ]['new'])) {
                $this->upload($collection, $fields[ $collection ]['new']);
            }

            if (!is_null($fields[ $collection ]['order'])) {
                $this->reorder($collection, $fields[ $collection ]['order']);
            }

            if (!is_null($fields[ $collection ]['deleted'])) {
                $this->delete($collection, $fields[ $collection ]['deleted']);
            }
        }

        return $this->listing;
    }

    /**
     * Gets the documents.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return collect([
            'photos' => $this->listing->getMedia('photos'),
            'files' => $this->listing->getMedia('files'),
        ]);
    }

    /**
     * Gets the documents for display.
     * Adds URL and other missing information to each document.
     *
     * @return Illuminate\Support\Collection
     */
    public function getForDisplay()
    {
        return $this->get()->map(function ($collection) {
            return $collection->map(function ($document) {
                $document->full_url = $document->getFullUrl();
                $document->url = $document->getUrl();
                $document->date = $document->created_at->format('n/j/Y');

                // Add photo sizes
                if ($document->collection_name === 'photos') {
                    $document->upload_url = $document->getFullUrl('photo_upload');
                    $document->featured_url = $document->getFullUrl('photo_featured');
                    $document->favorite_thumbnail_url = $document->getFullUrl('photo_favorite_thumbnail');
                    $document->roll_url = $document->getFullUrl('photo_roll');
                }

                // Add file sizes
                if ($document->collection_name === 'files') {
                    $document->thumbnail_url = $document->getFullUrl('file_thumbnail');
                }

                return $document;
            });
        });
    }

    /**
     * Uploads new files.
     *
     * @param  string $collection
     * @param  array $files
     */
    protected function upload($collection, $files)
    {
        foreach ($files as $key => $file) {
            $this->listing->addDocumentFromRequest($collection, "{$collection}.new.{$key}");
        }
    }

    /**
     * Reorders the media items.
     *
     * @param  string $collection
     * @param  array $ids
     */
    protected function reorder($collection, $ids)
    {
        $ids = $this->mergeInNewIds($collection, $ids);

        Media::setNewOrder($ids);
    }

    protected function mergeInNewIds($collection, $ids)
    {
        $ids = collect($ids);

        // Get the new fields.
        $fields = $this->getFields(request())[$collection] ?? [];
        $new = collect($fields['new'] ?? []);
        $orderFileNames = collect($fields['order-file-names'] ?? []);

        // Get the new ids.
        $newIds = collect($ids)->filter(function ($id) {
            return str_contains($id, 'new');
        });

        // Make sure we have new media and ids
        // If not something went wrong so we will drop any new ids
        // to avoid an unhelpful error.
        if ($newIds->isEmpty() or $new->isEmpty() or $orderFileNames->isEmpty()) {
            return $ids->filter(function ($id) {
                return !str_contains($id, 'new');
            })->toArray();
        }

        // Now we have to match up the new db ids in place of the new-* placeholders.
        $orderFileIds = $this->getOrderFileIds($orderFileNames, $collection);

        // Now we have to merge the new db ids in place of the new-* placeholders.
        return $this->mergeOrderFileIds($ids, $orderFileIds);
    }

    public function mergeOrderFileIds($ids, $orderFileIds)
    {
        return $ids->map(function ($id) use ($orderFileIds) {
            return str_contains($id, 'new-') ? $orderFileIds[$id] ?? null : $id;
        })->filter()->values()->toArray();
    }

    public function getOrderFileIds($orderFileNames, $collection, $documents = null)
    {
        $documentIds = $this->getDocumentIdsByFileName($collection, $documents);

        return $orderFileNames->mapWithKeys(function ($name, $key) use ($documentIds) {
            return [$key => $documentIds[$name] ?? null];
        })->filter();
    }

    protected function getDocumentIdsByFileName($collection, $documents = null)
    {
        $documents = $documents ?? $this->listing->documents()->get($collection);
        return $documents
        ->mapWithKeys(function ($media) {
            return [$media->file_name => $media->id];
        });
    }

    /**
     * Deletes files.
     *
     * @param  string $collection
     * @param  array $ids
     */
    protected function delete($collection, $ids)
    {
        $documents = $this->listing->documents()->get($collection);
        foreach ($documents->whereIn('id', $ids) as $document) {
            $document->forceDelete();
        }
    }

    /**
     * Gets the documents.
     *
     * @param  Request $request [description]
     *
     * @return array
     */
    protected function getFields(Request $request)
    {
        $fields =  $request->all($this->collections);

        return array_map(function ($field) {
            return [
                'new' => isset($field['new']) ? $field['new'] : null,
                'order' => isset($field['order']) ? $field['order'] : null,
                'deleted' => isset($field['deleted']) ? $field['deleted'] : null,
                'order-file-names' => isset($field['order-file-names']) ? $field['order-file-names'] : null,
            ];
        }, $fields);
    }
}
