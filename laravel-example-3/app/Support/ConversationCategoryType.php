<?php

namespace App\Support;

abstract class ConversationCategoryType
{
    const ACCOUNTING = 1;
    const DEAL_SPECIFICS = 2;
    const FINANCE_TAX = 3;
    const HUMAN_RESOURCES = 4;
    const INDUSTRY_COMPETITION = 5;
    const INFORMATION_TECHNOLOGY = 6;
    const LEGAL = 7;
    const MARKETING_SALES = 8;
    const REAL_ESTATE = 9;
    const STRATEGY = 10;
    const OTHER = 11;
    const BUYER_INQUIRY = 12;


    public static function getLabels()
    {
        return [
            self::ACCOUNTING => 'Accounting',
            self::DEAL_SPECIFICS => 'Deal Specifics',
            self::FINANCE_TAX => 'Finance/Tax',
            self::HUMAN_RESOURCES => 'Human Resources',
            self::INDUSTRY_COMPETITION => 'Industry/Competition',
            self::INFORMATION_TECHNOLOGY => 'Information Technology',
            self::LEGAL => 'Legal',
            self::MARKETING_SALES => 'Marketing/Sales',
            self::REAL_ESTATE => 'Real Estate',
            self::STRATEGY => 'Strategy',
            self::OTHER => 'Other',
            self::BUYER_INQUIRY => 'Business Inquiry'
        ];
    }

    public static function getValues()
    {
        return array_keys(self::getLabels());
    }

    public static function getLabel($value)
    {
        $values = self::getLabels();

        return isset($values[$value]) ? $values[$value] : null;
    }
}