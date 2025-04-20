<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Support\Styleguide\Notifications;
use App\Support\Notification\NotificationType;

class StyleguideController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.sections.styleguide.index', [
            'pageTitle' => 'Styleguide',
            'section' => 'styleguide',
        ]);
    }

    /**
     * Display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // So we have the ability to name the routes we can build the "slug" off of the route name.
        // Each route should be named as styleguide.show.{slug}
        $routeName = Route::getFacadeRoot()->current()->getName();
        $slug = str_replace('styleguide.show.', '', $routeName);
        $notifications = optional($this->getNotifications($slug));

        return view("app.sections.styleguide.show.{$slug}", [
            'pageTitle' => 'Styleguide',
            'pageSubtitle' => ucwords(str_replace(['-', '_'], ' ', $slug)),
            'section' => 'styleguide',
            'notifications' =>  $notifications->get('notifications'),
            'notification' => $notifications->get('notification'),
            'notification_ids' =>  $notifications->get('ids'),
            'notification_type' => intval($notifications->get('type')),
            'notification_labels' => NotificationType::getLabels()->toArray(),
        ]);
    }

    protected function getNotifications($slug)
    {
        switch ($slug) {
            case 'notification':
                $notifications = (new Notifications($noFallback = true));
                return collect([
                    'notification' => $notifications->getIndividual(),
                    'ids' => $notifications->modelIds(),
                    'type' => request()->get('type'),
                ]);
                break;

            case 'notifications':
                return (new Notifications)->getAll();
                break;

            default:
                return collect([]);
                break;
        }
    }

    protected function getNotification($slug)
    {
        return 'notifications' === $slug ? (new Notifications)->getIndividual() : collect([]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->getValidations());
    }

    protected function getValidations()
    {
        return $this->mergeRequiredValdiation([
            'input_email' => 'email',
            'input_number' => 'numeric',
            'input_price' => 'numeric',
            'range' => 'numeric',
            'input_checkbox' => 'boolean',
            'input_min_to_max_price_min' => 'numeric',
            'input_min_to_max_price_max' => 'numeric',
            'input_min_to_max_price_fixed_values_min' => 'numeric',
            'input_min_to_max_price_fixed_values_max' => 'numeric',
        ]);
    }

    protected function mergeRequiredValdiation($validations = [])
    {
        if (!request()->has('force_validation_errors')) {
            return $validations;
        }

        $validations = collect($validations);
        return collect($this->getInputNames())->flip()
        ->map(function ($value, $key) use ($validations) {
            return collect([ 'required', $validations->get($key) ])->filter()->implode('|');
        })->toArray();
    }

    protected function getInputNames()
    {
        return [
            'rating_example_rating',
            'rating_example_feedback',
            'input_url',
            'input_textarea',
            'input_text',
            'input_text_left',
            'input_text_right',
            'input_email',
            'input_hidden',
            'input_number',
            'input_password',
            'input_phone',
            'input_price',
            'input_price_negative_positive',
            'range',
            'input_toggle',
            'input_checkbox',
            'input_checkbox_boolean',
            'input_datepicker',
            'input_multi_checkbox',
            'input_radio',
            'input_min_to_max_price_min',
            'input_min_to_max_price_max',
            'input_min_to_max_price_fixed_values_min',
            'input_min_to_max_price_fixed_values_max',
            'input_business_category_select',
            'input_select',
            'disabled_unload',
        ];
    }
}
