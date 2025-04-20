<?php

namespace App;

use Closure;
use App\Listing;
use Stripe\Stripe;
use App\Conversation;
use Laravel\Spark\Spark;
use Laravel\Scout\Searchable;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\Media;
use Spatie\Image\Manipulations;
use App\Support\GetsCoordinates;
use App\Support\User\HasPhotoUrl;
use App\UserDesiredPurchaseCriteria;
use App\UserProfessionalInformation;
use Laravel\Spark\User as SparkUser;
use Illuminate\Support\Facades\Cache;
use Stripe\Customer as StripeCustomer;
use App\Support\User\HasBillingAttributes;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\NotificationType;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Support\Notification\ResetPasswordNotification;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class User extends SparkUser implements HasMediaConversions
{
    use HasMediaTrait;
    use HasPhotoUrl;
    use Searchable;
    use GetsCoordinates;
    use HasBillingAttributes;
    use HasNotifications;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'primary_roles',
        'photo_id',
        'listed_in_directory',
        'work_phone',
        'tagline',
        'bio',
        'display_inquiry_intro',
        'last_login',
        'getting_started_dismissed',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'country_code',
        'phone',
        'card_brand',
        'card_last_four',
        'card_country',
        'card_expiration_month',
        'card_expiration_year',
        'card_name',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
        'two_factor_reset_code',
        'last_login',
        'single_sign_on_token',
        'subscriptions',
        'stripe_id',
        'terms_accepted',
        'usa_resident',
        'tour',
        'getting_started_dismissed',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'last_login',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'uses_two_factor_auth' => 'boolean',
        'primary_roles' => 'array',
        'listed_in_directory' => 'boolean',
        'photo_id' => 'integer',
        'display_inquiry_intro' => 'boolean',
        'removed_by_admin' => 'boolean',
        'terms_accepted' => 'boolean',
        'usa_resident' => 'boolean',
        'getting_started_dismissed' => 'boolean',
    ];

    /**
     * The attribute defaults.
     *
     * @var array
     */
    protected $attributes = [
        'primary_roles' => '[]',
        'display_inquiry_intro' => true,
    ];

    protected $appends = [
        'name',
        'initials',
        'profile_url',
        'photo_thumbnail_small_url',
        'photo_upload_url',
        'photo_listing_seller_url',
        'photo_roll_url',
        'photo_featured_url',
        'last_login_string',
        'account_status_label',
        'occupation',
    ];

    public function __construct(array $attributes = array())
    {
        $this->setRawAttributes(array(
            'last_login' => $this->freshTimestamp(),
        ), true);

        parent::__construct($attributes);
    }

    protected function getOccupationAttribute()
    {
        return optional($this->professionalInformation)->occupation;
    }

    public function spaces()
    {
        return $this->hasMany('App\ExchangeSpace');
    }

    protected function conversations()
    {
        return $this->hasManyThrough('App\Conversation', 'App\ExchangeSpace');
    }

    public function spaceMembers()
    {
        return $this->hasMany('App\ExchangeSpaceMember');
    }

    public function spaceActiveMembers()
    {
        return $this->spaceMembers()->active()->approved();
    }

    /**
     * Get the desired purchase criteria record associated with the user.
     */
    public function desiredPurchaseCriteria()
    {
        return $this->hasOne('App\UserDesiredPurchaseCriteria');
    }

    /**
     * Get the professional information record associated with the user.
     */
    public function professionalInformation()
    {
        return $this->hasOne('App\UserProfessionalInformation');
    }

    /**
     * Get the listings records associated with the user.
     */
    public function listings()
    {
        return $this->hasMany('App\Listing');
    }

    /**
     * Get the listings records associated with the user.
     */
    public function favorites()
    {
        return $this->hasMany('App\Favorite');
    }

    /**
     * Get the saved search records associated with the user.
     */
    public function savedSearches()
    {
        return $this->hasMany('App\SavedSearch');
    }

    /**
     * Get the professional information record associated with the user.
     */
    public function emailNotificationSettings()
    {
        return $this->hasOne('App\UserEmailNotificationSettings');
    }

    /**
     * Get the user's name.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        if (!is_null($value)) {
            return $value;
        }

        return $this->attributes['name'] = trim(implode(' ', [
            $this->attributes['first_name'] ?? '',
            $this->attributes['last_name'] ?? '',
        ]));
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = $value;
        $this->attributes['name'] = trim(implode(' ', [
            $this->attributes['first_name'] ?? '',
            $this->attributes['last_name'] ?? '',
        ]));
    }

    /**
     * Set the user's last name.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = $value;
        $this->attributes['name'] = trim(implode(' ', [
            $this->attributes['first_name'] ?? '',
            $this->attributes['last_name'] ?? '',
        ]));
    }

    /**
     * Get the user's initials.
     *
     * @param  string  $value
     * @return string
     */
    public function getInitialsAttribute($value)
    {
        $firstName = $this->attributes['first_name'];
        $firstName = is_string($firstName) ? substr($firstName, 0, 1) : '';
        $lastName = $this->attributes['last_name'];
        $lastName = is_string($lastName) ? substr($lastName, 0, 1) : '';

        return strtoupper("{$firstName}{$lastName}");
    }

    /**
     * Get the user's initials.
     *
     * @param  string  $value
     * @return string
     */
    public function getShortNameAttribute($value)
    {
        return ucwords("{$this->first_name_initial}. {$this->last_name}");
    }

    /**
     * Get the user's profile URL.
     *
     * @param  string  $value
     * @return void
     */
    public function getProfileUrlAttribute($value)
    {
        return route('professional.show', ['id' => $this->id]);
    }

    /**
     * Get the user's profile edit URL.
     *
     * @param  string  $value
     * @return void
     */
    public function getProfileEditUrlAttribute($value)
    {
        return route('profile.edit');
    }


    /**
     * Get the user's work phone for display.
     *
     * @param  string  $value
     * @return void
     */
    public function getWorkPhoneDisplayAttribute($value)
    {
        if (!$this->work_phone) {
            return null;
        }

        return collect([
            substr($this->work_phone, 0, 3),
            substr($this->work_phone, 3, 3),
            substr($this->work_phone, 6, 4),
        ])->filter()->implode('.');
    }

    /**
     * Set the user's work phone.
     *
     * @param  string  $value
     * @return void
     */
    public function setWorkPhoneAttribute($value)
    {
        $this->attributes['work_phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Adds media file conversions.
     * @see https://docs.spatie.be/laravel-medialibrary/
     */
    public function registerMediaConversions(Media $media = null)
    {
        // When adding a new size here you also should for convenience.
        // Add default images to /public/img/defaults/user/{conversion}.jpg
        // Add custom model attribute accessor to HasPhotoUrl trait
        // Add the custom accessor to the $appends array

        $this->addMediaConversion('thumbnail_small')
                ->width(44)
                ->height(44)
                ->crop(Manipulations::CROP_CENTER, 44, 44)
                ->keepOriginalImageFormat()
                ->nonQueued();

        $this->addMediaConversion('upload')
                ->width(200)
                ->height(200)
                ->crop(Manipulations::CROP_CENTER, 200, 200)
                //->fit(Manipulations::FIT_FILL, 155, 155)
                ->keepOriginalImageFormat()
                ->nonQueued();

        $this->addMediaConversion('listing_seller')
                ->width(400)
                ->height(400)
                ->crop(Manipulations::CROP_CENTER, 400, 400)
                ->keepOriginalImageFormat();

        $this->addMediaConversion('roll')
                ->width(400)
                ->height(400)
                ->crop(Manipulations::CROP_CENTER, 400, 400)
                ->keepOriginalImageFormat()
                ->nonQueued();

        $this->addMediaConversion('featured')
                ->width(400)
                ->height(400)
                ->crop(Manipulations::CROP_CENTER, 400, 400)
                ->keepOriginalImageFormat();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        // Get the user.
        $user = collect($this->toArray())->only([
            'name',
            'tagline',
            'bio',
            'email',
        ])->toArray();

        // Get the professional information.
        $info = optional($this->professionalInformation)
                ->toSearchableArray();
        $info = is_null($info) ? [] : $info;

        // Get the criteria.
        $criteria = optional($this->desiredPurchaseCriteria)
                    ->toSearchableArray();
        $criteria = is_null($criteria) ? [] : $criteria;

        return array_merge($user, $info, $criteria);
    }

    public function scopeIsListed($query)
    {
        return $query->where('listed_in_directory', true);
    }

    /**
     * Get's the users active listings count.
     *
     * @return int
     */
    public function getActiveListingsCountAttribute()
    {
        return $this->listings->count();
    }

    /**
     * Gets listing favorite id.
     *
     * @param Listing $listing
     * @return void
     */
    public function listingFavoriteId(Listing $listing)
    {
        return optional($this->favorites->where('listing_id', $listing->id)->first())->id;
    }

    public function getShowPersonalInfoAttribute()
    {
        return $this->tagline || $this->bio;
    }

    public function getCountryCodeAttribute($value)
    {
        return is_null($value) ? 1 : $value;
    }

    public function getShowBusinessInterestsAttribute()
    {
        $items = $this->desiredPurchaseCriteria->business_categories;

        return $items ||
                isset($this->location) ||
                isset($this->desiredPurchaseCriteria->asking_price_minimum) ||
                isset($this->desiredPurchaseCriteria->asking_price_maximum) ||
                isset($this->desiredPurchaseCriteria->revenue_minimum) ||
                isset($this->desiredPurchaseCriteria->revenue_maximum) ||
                isset($this->desiredPurchaseCriteria->ebitda_minimum) ||
                isset($this->desiredPurchaseCriteria->ebitda_maximum) ||
                isset($this->desiredPurchaseCriteria->pre_tax_earning_minimum) ||
                isset($this->desiredPurchaseCriteria->pre_tax_earning_maximum) ||
                isset($this->desiredPurchaseCriteria->discretionary_cash_flow_minimum) ||
                isset($this->desiredPurchaseCriteria->discretionary_cash_flow_maximum);
    }

    public function getShowProfessionalInfoAttribute()
    {
        return $this->professionalInformation->occupation_label ||
                $this->professionalInformation->years_of_experience ||
                $this->professionalInformation->company_name ||
                $this->professionalInformation->links_with_protocol ||
                $this->professionalInformation->professional_background ||
                $this->professionalInformation->license_qualifications ||
                $this->professionalInformation->areas_served;
    }

    public function getLastLoginAttribute($value)
    {
        if (!is_null($value) and !($value instanceof Carbon)) {
            $value = new Carbon($value);
        }

        return is_null($value) ? $this->created_at : $value;
    }

    public function getLastLoginStringAttribute($value)
    {
        return optional($this->last_login)->toDateTimeString();
    }

    public function updateLastLogin()
    {
        $this->last_login = $this->freshTimestamp();

        return $this->save();
    }

    public function isDeveloper()
    {
        return Spark::developer($this->email);
    }

    public function isImpersonatorDeveloper()
    {
        $impersonatorId = session()->get('spark:impersonator');
        if (!$impersonatorId or $this->id === $impersonatorId) {
        }

        return optional(User::find(session()->get('spark:impersonator')))
            ->isDeveloper();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->dispatchNotification(
            new ResetPasswordNotification($this, $token)
        );
    }

    /**
     * Check if the user is the only/current sign on.
     *
     * @return boolean
     */
    public function isSingleSignOn()
    {
        if (!config('app.enable_single_sign_on')) {
            return true;
        }

        $userToken = $this->single_sign_on_token;
        $sessionToken = session()->get('single_sign_on_token');

        return $userToken === $sessionToken;
    }

    public function adminRemove()
    {
        $this->removed_by_admin = true;
        $this->save();

        $this->delete();
    }

    /**
     * Checks if the user has any un-trashed listings.
     *
     * @return boolean
     */
    public function hasListings(): bool
    {
        return !$this->listings()->get()->isEmpty();
    }

    /**
     * Checks if the user can close their account.
     *
     * @return boolean
     */
    public function canCloseAccount(): bool
    {
        if ($this->hasListings() or $this->hasActiveSubscription()) {
            return false;
        }

        return true;
    }

    public function hasActiveSubscription()
    {
        return $this->isSubscribed() and !$this->onGracePeriod();
    }

    public function cannotCloseAccountReason(): string
    {
        if ($this->hasListings()) {
            return $this->cannotCloseAccountBecauseListings();
        }

        return $this->cannotCloseAccountBecauseSubscribed();
    }

    public function cannotCloseAccountBecauseListings()
    {
        return "You must delete all listings before closing your account.";
    }

    public function cannotCloseAccountBecauseSubscribed()
    {
        return 'You must cancel your subscription before closing your account.';
    }

    protected function getCardLastFourDisplayAttribute()
    {
        if (!$this->card_last_four) {
            return null;
        }

        switch ($this->card_brand) {
            case 'American Express':
                return "****-******-{$this->card_last_four}";

            case 'Diners Club':
                return "****-****-****-{$this->card_last_four}";

            default:
                return "****-****-****-{$this->card_last_four}";
        }
    }

    public function addToStripe()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $customer = StripeCustomer::create(['email' => $this->email]);

        $this->stripe_id = $customer->id;

        $this->save();
    }

    public function dueDiligenceDigestLastSent()
    {
        return $this->due_diligence_digest_last_sent_at ?: now()->subDay();
    }

    public function myListings($count = 4)
    {
        return $this->listings()
        ->where('user_id', $this->id)
        ->where('display_listed_by', true)
        ->published()
        ->take(4)
        ->get();
    }

    public function tour()
    {
        return $this->hasOne('App\UserOverlayTour')->withDefault([
            'user_id' => $this->id,
        ]);
    }

    public function exchangeSpaceNotifications()
    {
        return $this->getUserExchangeSpaceNotifications($this->id)
        ->load('exchangeSpace')
        ->filter(function ($notification) {
            return !$notification->exchangeSpace->is_inquiry and !$notification->read;
        });
    }

    public function getInquiryIdsAttribute()
    {
        return $this->spaceActiveMembers->load('space')
        ->filter(function ($member) {
            return $member->space->is_inquiry;
        })->map(function ($member) {
            return $member->space->id;
        })->toArray();
    }

    public function getInquiryConversationNotificationsAttribute()
    {
        $conversationIds = Conversation::whereIn(
            'exchange_space_id',
            $this->inquiry_ids
        )->get()->pluck('id');

        return ConversationNotification::where('user_id', $this->id)
        ->where('read', false)
        ->where('user_id', $this->id)
        ->whereIn('conversation_id', $conversationIds)
        ->get();
    }

    public function getBuyerInquiryNotificationsAttribute()
    {
        return ExchangeSpaceNotification::whereIn(
            'exchange_space_id',
            $this->inquiry_ids
        )
        ->where('read', false)
        ->where('user_id', $this->id)
        ->get()
        ->concat($this->inquiry_conversation_notifications)
        ->sortBy('created_at');
    }

    public function getHasBuyerInquiryNotificationsAttribute()
    {
        return !$this->buyer_inquiry_notifications->isEmpty();
    }

    public function tourEnabled($tour)
    {
        // Some overrides to force the tour to run in specfic circumstances.
        if (request()->has('tour') or config('app.always_run_tour')) {
            return true;
        }

        // The exchange space index is special since it should not trigger until there is at least one exchange space.
        if ($tour === 'exchange_space_index' && $this->spaceMembers->count() === 0) {
            return false;
        }

        $column = "{$tour}_viewed";

        return !$this->tour->$column;
    }

    protected function getPreviewCacheKeyAttribute()
    {
        return "user.preview.{$this->id}";
    }

    public function emptyPreview()
    {
        Cache::forget($this->preview_cache_key);

        return $this;
    }

    public function fillPreview(Closure $callback)
    {
        $this->emptyPreview();
        Cache::rememberForever($this->preview_cache_key, $callback);

        return $this;
    }

    public function getPreview()
    {
        return Cache::get("user.preview.{$this->id}", $this);
    }

    public function getFirstNameInitialAttribute()
    {
        return $this->first_name ? substr($this->first_name, 0, 1) : '';
    }
}
