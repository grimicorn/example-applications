<?php

namespace App\Parsers;

use Zttp\Zttp;
use SimpleXMLElement;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Tightenco\Collect\Support\Collection;
use Rodenastyle\StreamParser\StreamParser;

class SitemapXML
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function parse()
    {
        if ($result = $this->read($this->url)) {
            return $result->pluck('loc');
        }

        return collect([]);
    }

    protected function isIndexFile($uri)
    {
        $extension = pathinfo($uri, PATHINFO_EXTENSION);

        return strtolower($extension) === 'xml';
    }

    protected function isGZipped($url)
    {
        $extension = pathinfo($this->url, PATHINFO_EXTENSION);

        return strtolower($extension) === 'gz';
    }

    protected function readGZipped($url)
    {
        $response = Zttp::get($url);

        if (!$response->isSuccess()) {
            return collect([]);
        }

        try {
            $body = gzdecode($response->body());
        } catch (\Exception $e) {
            $body = $response->body();
        }

        $name = md5(uniqid() . time() . auth()->id()) . '.xml';
        $file = Storage::disk('temporary')->put($name, $body);
        $path = storage_path("app/temp/{$name}");

        $result = $this->streamParser($path);

        Storage::disk('temporary')->delete($name);

        return $result;
    }

    protected function streamParser($uri)
    {
        $result = collect([]);

        if (!$uri) {
            return $result;
        }

        try {
            StreamParser::xml($uri)
            ->each(function (Collection $item) use (&$result) {
                $result->push($item);
            });

            return $result;
        } catch (\Exception $e) {
            return $result;
        }
    }

    protected function read(string $uri)
    {
        if ($this->isGZipped($uri)) {
            $data = $this->readGZipped($uri);
        } else {
            $data = $this->streamParser($uri);
        }

        $result = collect([]);
        $data->filter(function ($item) {
            return $item->has('loc');
        })->each(function ($item) use (&$result) {
            $uri = $item->get('loc');

            if ($this->isIndexFile($uri)) {
                $result = $result->concat(
                    $this->read($uri, true)
                );

                return;
            }

            $result->push($item);
        });

        return $result;
    }
}
