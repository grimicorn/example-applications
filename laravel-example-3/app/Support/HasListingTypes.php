<?php

namespace App\Support;

trait HasListingTypes
{
    public function getListingTypes()
    {
        return [
            'Existing Business',
            'Franchise Opportunity',
            'Asset Sale',
            'Commercial Real Estate',
            'Established Business',
        ];
    }

    public function getListingTypesForSelect()
    {
        return array_map(function ($type) {
            return [
                'label' => $type,
                'value' => $type,
            ];
        }, $this->getListingTypes());
    }
}
