<?php

namespace App\Support;

class FilePreview
{
    protected $fileUrl;

    public function __construct($fileUrl)
    {
        $this->fileUrl = $fileUrl;
    }

    public function isFileType($extension)
    {
        $extension = ltrim($extension, '.');

        try {
            return mime_content_type($this->fileUrl) === $this->getTypes()->get($extension);
        } catch (\Exception $e) {
            return pathinfo($this->fileUrl, PATHINFO_EXTENSION) === $extension;
        }
    }

    protected function getTypes()
    {
        return collect([
            'pdf' => 'application/pdf',
            'bmp' => 'image/bmp',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
        ]);
    }

    public function isSupportedFileType()
    {
        return !$this->getTypes()
        ->filter(function ($type, $extension) {
            return $this->isFileType($extension);
        })->isEmpty();
    }

    public function isImage()
    {
        // Currently the only supported non-image type is a pdf
        return !$this->isFileType('pdf');
    }
}
