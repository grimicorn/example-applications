<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class RecaptchaValidator extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('recaptcha', function ($field, $token, $parameters, $validator) {

            // check the token that has been passed by a CURL call to the server

            $payload = [
                'secret'   => config('services.recaptcha.secret_key'),
                'remoteip' => app('request')->getClientIp(),
                'response' => $token,
            ];

            $ch = curl_init(config('services.recaptcha.url'));

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $recaptchaReply = json_decode(curl_exec($ch));

            curl_close($ch);

            if(isset($recaptchaReply->success)) {
                return $recaptchaReply->success;
            }
            return false;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
