<?php

namespace App;

use App\BaseModel;
use App\Support\HasStates;
use Spatie\MediaLibrary\Media;
use App\Support\GetsCoordinates;
use App\Support\HasLinksAttribute;
use App\Support\User\HasCompanyLogoUrl;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class UserProfessionalInformation extends BaseModel implements HasMediaConversions
{
    use HasMediaTrait, HasCompanyLogoUrl, GetsCoordinates, HasStates;
    use HasLinksAttribute;


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'areas_served' => 'array',
        'links' => 'array',
        'ibba_designation' => 'boolean',
        'cbi_designation' => 'boolean',
        'm_a_source_designation' => 'boolean',
        'm_ami_designation' => 'boolean',
        'am_aa_designation' => 'boolean',
        'abi_designation' => 'boolean',
        'other_designations' => 'array',
        'years_of_experience' => 'integer',
        'company_logo_id' => 'integer',
    ];

    /**
     * The attribute defaults.
     *
     * @var array
     */
    protected $attributes = [
        'areas_served' => '[]',
        'links' => '[]',
        'ibba_designation' => false,
        'cbi_designation' => false,
        'm_a_source_designation' => false,
        'm_ami_designation' => false,
        'am_aa_designation' => false,
        'abi_designation' => false,
        'other_designations' => '[]',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'occupation',
        'years_of_experience',
        'company_name',
        'links',
        'professional_background',
        'areas_served',
        'ibba_designation',
        'cbi_designation',
        'm_a_source_designation',
        'm_ami_designation',
        'am_aa_designation',
        'abi_designation',
        'other_designations',
        'company_logo_id',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip_code',
    ];

    protected $appends = [
        'state_unabbreviated',
    ];

    /**
     * Get the user's address
     *
     * @return string
     */
    public function getAddressAttribute()
    {
        if (
            isset($this->address_1) &&
            isset($this->city) &&
            isset($this->state) &&
            isset($this->zip_code)
        ) {
            return implode(', ', [
                implode(' ', [$this->address_1, $this->address_2]),
                $this->city,
                implode(' ', [$this->state, $this->zip_code]),
            ]);
        }
    }

    /**
     * Gets the user's Areas Served.
     *
     * @return string
     */
    protected function getAreasServedListAttribute()
    {
        return collect($this->areas_served)->map(function ($area) {
            return implode(', ', $area);
        })->filter()->toArray();
    }

    protected function getUnabbreviatedAreasServedListAttribute()
    {
        return collect($this->areas_served)->map(function ($area) {
            return $this->getStateUnabbreviated($area['state']);
        })->filter()->toArray();
    }

    /**
     * Gets the user's occupation label.
     *
     * @return string
     */
    protected function getOccupationLabelAttribute()
    {
        return $this->occupation;
    }

    /**
     * Gets the user's Business Broker Designations.
     *
     * @return string
     */
    protected function getLicenseQualificationsAttribute()
    {
        $designations = array_filter([
            (bool) $this->ibba_designation ? 'IBBA' : null,
            (bool) $this->cbi_designation ? 'CBI' : null,
            (bool) $this->m_a_source_designation ? 'M&A Source' : null,
            (bool) $this->m_ami_designation ? 'M&AMI' : null,
            (bool) $this->am_aa_designation ? 'AM&AA' : null,
            (bool) $this->abi_designation ? 'ABI' : null,
        ]);
        $other_designations = $this->other_designations ? $this->other_designations : [];


        return array_merge($designations, $other_designations);
    }

    /**
     * Get the user that owns the listing.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Adds media file conversions.
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('upload')
                ->width(155)
                ->height(155)
                ->keepOriginalImageFormat();

        $this->addMediaConversion('thumbnail')
                ->width(350)
                ->height(90)
                ->keepOriginalImageFormat();
    }

    public function getStateUnabbreviatedAttribute()
    {
        return $this->getStateUnabbreviated($this->state);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $data = collect($this->toArray())->only([
            'city',
            'state',
            'company_name',
            'professional_background',
            'areas_served',
            'state_unabbreviated',
        ])->toArray();


        $data['license_qualifications'] = $this->license_qualifications;

        // Add the geolocation.
        if ($this->zip_code) {
            $data['_geoloc'] = $this->getZipCodeCoordinates($this->zip_code);
        }

        return $data;
    }
}
