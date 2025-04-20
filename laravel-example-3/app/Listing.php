<?php

namespace App;

use App\User;
use App\BaseModel;
use App\Support\HasStates;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\Media;
use Illuminate\Support\Carbon;
use App\Support\ChecksIsOwner;
use Spatie\Image\Manipulations;
use App\Support\GetsCoordinates;
use App\Support\HasLinksAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Support\Listing\HasDocuments;
use App\Support\ExchangeSpaceDealType;
use Illuminate\Database\Eloquent\Model;
use App\Support\ExchangeSpaceStatusType;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Support\HistoricalFinancial\HasYearlyDataHelpers;
use App\Support\ListingCompletionScore\ListingCompletionScore;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Listing extends BaseModel implements HasMediaConversions
{
    use HasMediaTrait;
    use HasDocuments;
    use Searchable;
    use GetsCoordinates;
    use HasYearlyDataHelpers;
    use ChecksIsOwner;
    use SoftDeletes;
    use HasStates;
    use HasLinksAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'business_name',
        'asking_price',
        'name_visible',
        'summary_business_description',
        'business_description',
        'business_category_id',
        'business_sub_category_id',
        'year_established',
        'number_of_employees',
        'revenue',
        'discretionary_cash_flow',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip_code',
        'address_visible',
        'location_description',
        'pre_tax_earnings',
        'ebitda',
        'real_estate_included',
        'real_estate_estimated',
        'real_estate_description',
        'fixtures_equipment_included',
        'fixtures_equipment_estimated',
        'fixtures_equipment_description',
        'inventory_included',
        'inventory_estimated',
        'inventory_description',
        'products_services',
        'market_overview',
        'competitive_position',
        'business_performance_outlook',
        'financing_available',
        'financing_available_description',
        'support_training',
        'support_training_description',
        'reason_for_selling',
        'desired_sale_date',
        'seller_non_compete',
        'hf_most_recent_year',
        'hf_most_recent_quarter',
        'links',
        'display_listed_by',
        'custom_label_1',
        'custom_input_1',
        'custom_label_2',
        'custom_input_2',
        'custom_label_3',
        'custom_input_3',
        'custom_label_4',
        'custom_input_4',
        'custom_label_5',
        'custom_input_5',
        'should_display_encouragement_modal'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
            'user_id' => 'integer',
            'asking_price' => 'integer',
            'name_visible' => 'boolean',
            'business_category_id' => 'integer',
            'business_sub_category_id' => 'integer',
            'number_of_employees' => 'string',
            'revenue' => 'integer',
            'discretionary_cash_flow' => 'integer',
            'address_visible' => 'boolean',
            'pre_tax_earnings' => 'integer',
            'ebitda' => 'integer',
            'real_estate_included' => 'integer',
            'real_estate_estimated' => 'integer',
            'real_estate_description' => 'string',
            'fixtures_equipment_included' => 'integer',
            'fixtures_equipment_estimated' => 'integer',
            'fixtures_equipment_description' => 'string',
            'inventory_included' => 'integer',
            'inventory_estimated' => 'integer',
            'financing_available' => 'boolean',
            'support_training' => 'boolean',
            'seller_non_compete' => 'boolean',
            'published' => 'boolean',
            'links' => 'array',
            'hf_most_recent_year' => 'date',
            'display_listed_by' => 'boolean',
            'custom_label_1' => 'string',
            'custom_input_1' => 'string',
            'custom_label_2' => 'string',
            'custom_input_2' => 'string',
            'custom_label_3' => 'string',
            'custom_input_3' => 'string',
            'custom_label_4' => 'string',
            'custom_input_4' => 'string',
            'custom_label_5' => 'string',
            'custom_input_5' => 'string',
            'should_display_encouragement_modal' => 'boolean',
    ];

    protected $appends = [
        'current_user_favorite_id',
        'show_url',
        'edit_url',
        'inquiry_create_url',
        'current_user_is_owner',
        'state_unabbreviated',
        'cover_photo_favorite_thumbnail_url',
        'current_user_active_space',
        'address',
    ];

    /**
     * The attribute defaults.
     *
     * @var array
     */
    protected $attributes = [
        'links' => '[]',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'spaces',
        'revenues',
        'expenses',
        'historicalFinancials',
        'name',
        'user',
        'listingCompletionScoreTotal',
        'media',
        'hf_most_recent_year',
        'hf_most_recent_quarter',
        'invoice_provider_id',
        'address_visible',
        'business_description',
        'products_services',
        'seller_non_compete',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip_code',
        'user_id',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['spaces'];

    /**
     * Get the listing's formatted asking price.
     *
     * @param  string  $value
     * @return string
     */
    public function getAskingPriceFormattedAttribute()
    {
        if (is_null($this->asking_price)) {
            return $this->asking_price;
        }

        return '$' . number_format($this->asking_price, 2);
    }

    public function getBusinessNameAttribute($value)
    {
        if ($this->current_user_is_owner) {
            return $value;
        }

        return $this->name_visible ? $value : '';
    }

    /**
     * Get the user that owns the listing.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getScoreTotalAttribute()
    {
        return $this->listing_completion_score->total();
    }

    public function getScoreTotalPercentageAttribute()
    {
        return $this->listing_completion_score->totalPercentage();
    }

    public function getScoreTotalPercentageForDisplayAttribute()
    {
        return $this->listing_completion_score
        ->totalPercentageForDisplay();
    }

    /**
     * Get the listings exchange spaces.
     */
    public function spaces()
    {
        return $this->hasMany('App\ExchangeSpace');
    }

    public function historicalFinancials()
    {
        return $this->hasMany('App\HistoricalFinancial');
    }

    public function getAddressAttribute()
    {
        if (!$this->address_visible) {
            $address = collect([$this->city, $this->state])->filter()->implode(', ');
        } else {
            $address = collect([
                collect([$this->address_1, $this->address_2,])->filter()->implode(' '),
                $this->city,
                collect([$this->state, $this->zip_code,])->implode(' '),
            ])->filter()->implode(', ');
        }

        $address = trim($address);
        return $address ? $address : null;
    }

    public function getCurrentUserFavoriteIdAttribute()
    {
        if (Auth::check()) {
            return Auth::user()->listingFavoriteId($this);
        }
    }

    /**
     * Adds media file conversions.
     * @see https://docs.spatie.be/laravel-medialibrary/
     */
    public function registerMediaConversions(Media $media = null)
    {
        // When adding a new size here you also should for convenience.
        // Add default images to /public/img/defaults/listing/{conversion}.jpg
        // Add custom model attribute accessor to HasCoverPhotoUrl trait
        // Add the custom accessor to the $appends array

        // Photos Collection
        $this->addMediaConversion('photo_upload')
                ->width(200)
                ->height(200)
                ->crop(Manipulations::CROP_CENTER, 200, 200)
                ->keepOriginalImageFormat()
                ->performOnCollections($this->photosCollectionKey)
                ->nonQueued();

        $this->addMediaConversion('photo_featured')
                ->width(670)
                ->height(450)
                ->keepOriginalImageFormat()
                ->performOnCollections($this->photosCollectionKey);

        $this->addMediaConversion('photo_favorite_thumbnail')
                ->width(400)
                ->height(400)
                ->crop(Manipulations::CROP_CENTER, 400, 400)
                ->keepOriginalImageFormat()
                ->performOnCollections($this->photosCollectionKey);

        $this->addMediaConversion('photo_roll')
                ->width(400)
                ->height(400)
                ->crop(Manipulations::CROP_CENTER, 400, 400)
                ->keepOriginalImageFormat();

        // Files collection
        $this->addMediaConversion('file_thumbnail')
                ->width(150)
                ->height(100)
                ->keepOriginalImageFormat()
                ->performOnCollections($this->filesCollectionKey);
    }

    public function getStateUnabbreviatedAttribute()
    {
        return $this->getStateUnabbreviated($this->state);
    }

    protected function getBusinessCategoriesAttribute()
    {
        return BusinessCategory::whereIn('id', array_filter([
            $this->business_category_id,
            $this->business_sub_category_id,
        ]))->get();
    }

    protected function isSearchable()
    {
        $restrictionDisabled = (bool) config('app.disable_listing_search_publish_restriction');

        return $restrictionDisabled ? true : (bool) $this->published;
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeIgnorePublishStatus($query)
    {
        return $query->where('published', true)->orWhere('published', false);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        if (!$this->isSearchable()) {
            return [];
        }

        $listing = [
            'title' => $this->title,
            'city' => $this->city,
            'state' => $this->state,
            'state_unabbreviated' => $this->state_unabbreviated,
            'summary_business_description' => $this->summary_business_description,
            'business_description' => $this->business_description,
            'products_services' => $this->products_services,
        ];

        if ($this->name_visible and isset($this->getAttributes()['business_name'])) {
            $listing['business_name'] = $this->getAttributes()['business_name'];
        }

        // Add the Listed By if displayed.
        if ($this->display_listed_by) {
            $listing['listed_by_name'] = $this->user->name;
            $listing['listed_by_company_name'] = $this->user
            ->professionalInformation->company_name;
        }

        // Add the geolocation.
        if ($this->zip_code) {
            $listing['_geoloc'] = $this->getZipCodeCoordinates($this->zip_code);
        }

        return $listing;
    }

    /**
     * Gets the listing' s show URL .
                *
                * @return string
     */
    public function getShowUrlAttribute()
    {
        return route('businesses.show', ['id' => $this->id]);
    }



    /**
     * Gets the listing's edit URL.
     *
     * @return string
     */
    public function getEditUrlAttribute()
    {
        return route('listing.details.edit', ['id' => $this->id]);
    }

    /**
     * Gets the listing's inquiry create URL.
     *
     * @return string
     */
    public function getInquiryCreateUrlAttribute()
    {
        return route('business-inquiry.store');
    }

    public function getListingCompletionScoreAttribute()
    {
        return new ListingCompletionScore($this);
    }

    public function expenses()
    {
        return $this->hasMany('App\Expense');
    }

    public function revenues()
    {
        return $this->hasMany('App\Revenue');
    }

    public function expenseTotals()
    {
        $lines = $this->expenses->map->linesInYearRange()->map->keyBy(function ($item) {
            return $item->year->format('Y');
        })
        ->map(function ($item) {
            return $item->map->amount;
        });

        return $this->yearRange($this->hfYearStart())->map(function ($year) use ($lines) {
            return $lines->pluck($year)->sum();
        });
    }

    public function listingCompletionScoreTotal()
    {
        return $this->hasOne('App\ListingCompletionScoreTotal');
    }

    public function exitSurvey()
    {
        return $this->hasOne('App\ListingExitSurvey');
    }

    public function getCurrentUserIsOwnerAttribute()
    {
        if (auth()->check() and $this->user_id === auth()->id()) {
            return true;
        }

        return false;
    }

    public function currentUserBuyerExchangeSpace()
    {
        if (!auth()->check()) {
            return null;
        }

        // We only want the buyer since the seller would be multiple.
        if ($this->current_user_is_owner) {
            return null;
        }

        return $this->spaces->reject(function ($space) {
            return $space->members
                   ->where('user_id', auth()->id())
                   ->isEmpty();
        })->first();
    }

    public function isRepublishable()
    {
        return !is_null($this->invoice_provider_id);
    }

    public function hasCompletedSpace()
    {
        return $this->spaces()->withTrashed()->get()
        ->pluck('deal')
        ->contains(ExchangeSpaceDealType::COMPLETE);
    }

    protected function userRemovedByAdmin()
    {
        return (bool) optional($this->user()->withTrashed()->get()->first())->removed_by_admin;
    }

    protected function userRemoved()
    {
        return (bool) optional($this->user()->withTrashed()->get()->first())->trashed();
    }

    public function saveExitSurvey()
    {
        return ListingExitSurvey::firstOrCreate(
            [
                'listing_id' => $this->id,
            ],
            array_filter([
                'final_sale_price' => request()->get('final_sale_price'),
                'overall_experience_rating' => request()->get('overall_experience_rating'),
                'overall_experience_feedback' => request()->get('overall_experience_feedback'),
                'products_services' => request()->get('products_services'),
                'participant_message' => request()->get('participant_message'),
                'sale_completed' => $this->hasCompletedSpace(),
            ])
        );
    }

    public function getFinancialsForYear($year)
    {
        $matchYear = intval($this->yearRange($this->hfYearStart())->get("year{$year}"));
        return $this->historicalFinancials->filter(function ($financial) use ($matchYear) {
            if (is_null($financial->year)) {
                return false;
            }

            return $matchYear === intval($financial->year->format('Y'));
        })->first();
    }

    public function setHfMostRecentQuarterAttribute($value)
    {
        if (is_numeric($value)) {
            $value = intval($value);
        }

        $this->attributes['hf_most_recent_quarter'] = $value;
    }

    public function participantMessage(?ExchangeSpace $space = null)
    {
        $space = optional($space);

        // Try to first get the exchange spaces close message.
        if (!is_null($space->close_message)) {
            return $space->close_message;
        }

        // Then try to get the exit survey message.
        if (!is_null(optional($this->exitSurvey)->participant_message)) {
            return optional($this->exitSurvey)->participant_message;
        }

        // Display the user left message if the buyer has left and
        // the seller has not deleted the space yet.
        if ($space->buyerHasLeft() and !$space->trashed()) {
            return 'The Buyer has left this Exchange Space.  As a result, all other members have been removed.';
        }

        return $this->default_participant_message;
    }

    public function getDefaultParticipantMessageAttribute()
    {
        return 'The seller was given an opportunity to provide a message to be shared with the members of the Exchange Space but declined to do so.';
    }

    public function getUserRemovedMessageAttribute()
    {
        return 'Deleted due to user leaving the platform';
    }

    public function getShouldDisplayStaleDataAlertAttribute()
    {
        $isEditRoute = collect([
            'listing.completion-score.index',
            'listing.historical-financials.edit',
        ])->contains(Route::getFacadeRoot()->current()->getName());

        return $isEditRoute and $this->listingCompletionScore->hasStaleData();
    }

    public function hfYearStart()
    {
        return $this->createYear(
            optional($this->hf_most_recent_year)->format('Y')
        )->format('Y');
    }

    public function hfFinalYear()
    {
        return intval(
            Carbon::createFromDate($this->hfYearStart())->addYear()->format('Y')
        );
    }

    public function getQuarterLabel()
    {
        switch (intval($this->hf_most_recent_quarter)) {
            case 1:
                return 'Q1';
                break;

            case 2:
                return 'Q2';
                break;

            case 3:
                return 'Q3';
                break;

            default:
                return '';
                break;
        }
    }

    public function isHfFinalYear($year)
    {
        return intval($year) === intval($this->hfFinalYear());
    }

    public function userHasActiveSpace($user)
    {
        return !is_null($this->userActiveSpace($user));
    }

    protected function userActiveSpace($user)
    {
        if (!$user) {
            return null;
        }

        return $this->spaces()->where('status', '!=', ExchangeSpaceStatusType::REJECTED)->with('allMembers')->get()
        ->filter(function ($space) use ($user) {
            return optional($space->allMembers->where('user_id', $user->id)->first())->active;
        })->first();
    }

    public function getCurrentUserActiveSpaceAttribute()
    {
        return $this->userActiveSpace(auth()->user());
    }

    public function transitioningToPublished()
    {
        if (!$this->published) {
            return false;
        }

        return collect($this->getOriginal())->get('published') !== $this->published;
    }
}
