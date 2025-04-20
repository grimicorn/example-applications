<?php

namespace App\Support;

trait HasOccupations
{
    public function getUserOccupations()
    {
        return [
            'Accountant',
            'Attorney',
            'Business Appraiser',
            'Business Broker',
            'Business Owner',
            'Consultant',
            'Financial Advisor',
            'Insurance Agent',
            'Investor',
            'Loan Officer',
        ];
    }
}
