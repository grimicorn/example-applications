<?php

namespace App\Imports;

use App\Domain\Supports\GoogleMapLinkToLocation;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocationImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $user = User::where('email', 'dtholloran@gmail.com')->first();
        r_collect($rows)->each(function (Collection $row) use ($user) {
            (new GoogleMapLinkToLocation)->firstOrCreate(
                $row->get('google_maps_link'),
                $row->except('google_maps_link')->map(function (?string $value, string $key) {
                    return $this->convertValueForDatabase($value, $key);
                })->toArray(),
                $user
            );
        });
    }

    protected function convertValueForDatabase(?string $value, string $key)
    {
        if ($key === 'visited') {
            $value = $value === 'true';
        }

        if ($value === '') {
            $value = null;
        }

        return $value;
    }
}
