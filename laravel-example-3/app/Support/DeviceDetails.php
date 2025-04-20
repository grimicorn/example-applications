<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use App\Support\DetectDevice\Detect;
use function GuzzleHttp\json_decode;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;

class DeviceDetails
{
    protected $locationRequest;

    public function get()
    {
        if (app()->environment('testing')) {
            return collect([]);
        }

        return collect([
            'type' => $this->type(),
            'browser' => $this->browser(),
            'operating_system' => $this->operatingSystem(),
            'ip_address' => $this->ipAddress(),
            'location' => $this->location(),
            'timestamp' => $this->timestamp(),
        ]);
    }

    public function timestamp($format = 'l, F dS, Y h:i:s A T')
    {
        try {
            return Carbon::now($this->locationRequest()->get('time_zone'))->format($format);
        } catch (\Exception $e) {
            return Carbon::now()->format($format);
        }
    }

    protected function location()
    {
        $response = $this->locationRequest()->only(['country_name', 'region_code', 'city'])->filter();

        if ($response->isEmpty()) {
            return 'Unavailable';
        }

        return "{$response->get('city')}, {$response->get('region_code')} - {$response->get('country_name')}";
    }

    protected function locationRequest()
    {
        // "Cache" the request for this request.
        if (isset($this->locationRequest)) {
            return $this->locationRequest;
        }

        // If the "Cache" is empty then make the request.
        try {
            $response = (new HttpClient)->request('GET', 'http://freegeoip.net/json/' . $this->ipAddress());
            $body = (string) $response->getBody();

            return $this->locationRequest = $body ? collect(json_decode($body, true)) : collect([]);
        } catch (ClientException $e) {
            return $this->locationRequest = collect([]);
        }
    }

    public function type()
    {
        return Detect::deviceType();
    }

    public function browser()
    {
        return Detect::browser();
    }

    public function operatingSystem()
    {
        return Detect::os();
    }

    public function ipAddress()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'] ?? '';
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        }

        return $_SERVER['REMOTE_ADDR'] ?? '';
    }
}
