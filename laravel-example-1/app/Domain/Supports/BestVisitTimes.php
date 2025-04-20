<?php

namespace App\Domain\Supports;

class BestVisitTimes
{
    public function ofYear()
    {
        return collect([
            'Spring',
            'Summer',
            'Fall',
            'Winter',
            'Spring/Summer',
            'Spring/Summer/Fall',
            'Spring/Fall',
            'Fall/Winter',
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ]);
    }

    public function OfDay()
    {
        return collect([
          'Sunrise',
          'Morning',
          'Midday',
          'After Noon',
          'Sunset',
          'Evening',
          'Night',
      ]);
    }
}
