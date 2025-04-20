<?php

namespace Tests\Support;

use Illuminate\Http\UploadedFile;

trait HasTestFiles
{
    /**
     * Gets a set of test files.
     *
     * @param  integer $count
     * @param  string  $extension
     * @param  string  $basename
     *
     * @return array
     */
    protected function getTestFiles($count = 1, $extension = 'jpg', $basename = 'file')
    {
        $files = [];

        for ($i = 1; $i <= $count; $i++) {
            $files[] = UploadedFile::fake()->image("{$basename}{$i}.{$extension}");
        }

        return $files;
    }
}
