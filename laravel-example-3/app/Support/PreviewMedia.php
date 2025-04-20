<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class PreviewMedia
{
    public $id;
    public $full_url;
    public $name;
    public $file_name;
    public $mime_type;
    public $size;
    public $created_at;
    public $updated_at;

    public function __construct($file)
    {
        $this->id = '';
        $this->file_name = $file->getClientOriginalName();
        $this->name = pathinfo($this->file_name, PATHINFO_FILENAME);
        $this->full_url = $this->getBase64Encoded($file);
        $this->created_at = $this->updated_at = Carbon::now();
        $this->mime_type = $file->getMimeType();
        $this->size = $file->getSize();
        $this->preview = true;
    }

    protected function getBase64Encoded($file)
    {
        $base64 = base64_encode(file_get_contents($file->getPathname()));

        return "data:{$this->mime_type};base64,{$base64}";
    }

    /**
     * Gets the preview full URL.
     *
     * @return void
     */
    public function getFullUrl()
    {
        return $this->full_url;
    }
}
