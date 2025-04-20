<?php

namespace App\Support\User;

use App\Support\HasForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateDesiredPurchaseCriteria
{
    protected $user;
    protected $request;
    protected $criteria;
    protected $preview;

    use HasForms;

    public function __construct(Request $request, $preview = false)
    {
        $this->user = Auth::user();
        $this->request = $request;
        $this->preview = $preview;
        $this->criteria = $this->user->desiredPurchaseCriteria;
    }

    /**
     * Update the users professional criteria.
     *
     * @return \App\UserDesiredPurchaseCriteria
     */
    public function update()
    {
        // Get fields
        $fields = collect($this->request->get('desiredPurchaseCriteria'))
                     ->only($this->keys())
                     ->toArray();

        // Massage the fields before saving.
//        $fields = $this->filterSubmittedArray('locations', $fields);
        $fields = $this->setBusinessCategories($fields);

        // Update the user
        if ($this->preview) {
            $this->criteria->fill($fields);
        } else {
            $this->criteria->update($fields);
        }

        return $this->criteria;
    }

    /**
     * Handle business categories.
     *
     * @param array $fields
     *
     * @return array
     */
    protected function setBusinessCategories($fields)
    {
        if (isset($fields['business_categories'])) {
            $fields['business_categories'] = array_map(function ($category) {
                return intval($category);
            }, $fields['business_categories']);
        }

        return $fields;
    }

    /**
     * Get the user keys.
     *
     * @return array
     */
    protected function keys()
    {
        return $this->criteria->getFillable();
    }
}
