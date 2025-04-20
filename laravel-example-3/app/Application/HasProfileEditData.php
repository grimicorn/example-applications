<?php

namespace App\Application;

trait HasProfileEditData
{
    /**
     * Gets an occupation.
     *
     * @return string
     */
    protected function getOccupations()
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
    /**
     * Gets test license qualifications.
     *
     * @return array
     */
    protected function getDesignations()
    {
        return [
            'ibba_designation' => 'IBBA',
            'cbi_designation' => 'CBI',
            'm_a_source_designation' => 'M&A Source',
            'm_ami_designation' => 'M&AMI',
            'am_aa_designation' => 'AM&AA',
            'abi_designation' => 'ABI',
        ];
    }
}
