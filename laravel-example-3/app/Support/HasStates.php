<?php

namespace App\Support;

trait HasStates
{
    protected function getStates()
    {
        $states = [
            'AL' => 'Alabama',
            'MT' => 'Montana',
            'AK' => 'Alaska',
            'NE' => 'Nebraska',
            'AZ' => 'Arizona',
            'NV' => 'Nevada',
            'AR' => 'Arkansas',
            'NH' => 'New Hampshire',
            'CA' => 'California',
            'NJ' => 'New Jersey',
            'CO' => 'Colorado',
            'NM' => 'New Mexico',
            'CT' => 'Connecticut',
            'NY' => 'New York',
            'DE' => 'Delaware',
            'NC' => 'North Carolina',
            'FL' => 'Florida',
            'ND' => 'North Dakota',
            'GA' => 'Georgia',
            'OH' => 'Ohio',
            'HI' => 'Hawaii',
            'OK' => 'Oklahoma',
            'ID' => 'Idaho',
            'OR' => 'Oregon',
            'IL' => 'Illinois',
            'PA' => 'Pennsylvania',
            'IN' => 'Indiana',
            'RI' => 'Rhode Island',
            'IA' => 'Iowa',
            'SC' => 'South Carolina',
            'KS' => 'Kansas',
            'SD' => 'South Dakota',
            'KY' => 'Kentucky',
            'TN' => 'Tennessee',
            'LA' => 'Louisiana',
            'TX' => 'Texas',
            'ME' => 'Maine',
            'UT' => 'Utah',
            'MD' => 'Maryland',
            'VT' => 'Vermont',
            'MA' => 'Massachusetts',
            'VA' => 'Virginia',
            'MI' => 'Michigan',
            'WA' => 'Washington',
            'MN' => 'Minnesota',
            'WV' => 'West Virginia',
            'MS' => 'Mississippi',
            'WI' => 'Wisconsin',
            'MO' => 'Missouri',
            'WY' => 'Wyoming',
            'DC' => 'District of Columbia',
        ];

        asort($states);

        return $states;
    }

    /**
     * Gets a states unabbreviated label.
     *
     * @param string $abbreviation
     * @return void
     */
    protected function getStateUnabbreviated($abbreviation)
    {
        $abbreviation = $abbreviation ? strtoupper($abbreviation) : '';
        $abbreviation = trim($abbreviation);
        $states = $this->getStates();
        if (isset($states[$abbreviation])) {
            return $states[$abbreviation];
        }
    }

    protected function getStatesForSelect()
    {
        $options = [];

        foreach ($this->getStates() as $abbreviation => $name) {
            $options[] = [
                'label' => $name,
                'value' => $abbreviation,
            ];
        }

        return $options;
    }
}
