<?php

namespace Database\Seeders;

use App\Models\LocationIcon;
use Illuminate\Database\Seeder;

class LocationIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->icons()->each(function ($icon) {
            if ($icon->id ?? null) {
                return LocationIcon::firstOrCreate(
                    [
                        'id' => intval($icon->id) ?? null,
                    ],
                    [
                        'name' => $icon->name,
                        'filename' => $icon->filename,
                    ]
                );
            }

            LocationIcon::firstOrCreate([
                'name' => $icon->name,
                'filename' => $icon->filename,
            ]);
        });
    }

    protected function icons()
    {
        // Icons are in /public/images/google-map-icons/
        // Originally sourced from https://mapicons.mapsmarker.com
        //
        // NOTE: Ids are hold over from originally not being in DB. They are not needed for new icons.

        return collect([
            [
                'name' => 'House',
                'id' => 1,
                'filename' => 'house.png',
            ],
            [
                'name' => 'Lodging',
                'id' => 2,
                'filename' => 'hotel_0star.png',
            ],
            [
                'name' => 'Bridge',
                'id' => 3,
                'filename' => 'bridge_modern.png',
            ],
            [
                'name' => 'Waterfall',
                'id' => 4,
                'filename' => 'waterfall-2.png',
            ],
            [
                'name' => 'Mountain',
                'id' => 5,
                'filename' => 'mountains.png',
            ],
            [
                'name' => 'Forest',
                'id' => 6,
                'filename' => 'forest2.png',
            ],
            [
                'name' => 'River',
                'id' => 7,
                'filename' => 'river-2.png',
            ],
            [
                'name' => 'Beach',
                'id' => 8,
                'filename' => 'beach_icon.png',
            ],
            [
                'name' => 'Lake',
                'id' => 9,
                'filename' => 'lake.png',
            ],
            [
                'name' => 'Food',
                'id' => 10,
                'filename' => 'fastfood.png',
            ],
            [
                'name' => 'Museum Archeological',
                'id' => 11,
                'filename' => 'museum_archeological.png',
            ],
            [
                'name' => 'Museum Art',
                'id' => 12,
                'filename' => 'museum_art.png',
            ],
            [
                'name' => 'Museum Crafts',
                'id' => 13,
                'filename' => 'museum_crafts.png',
            ],
            [
                'name' => 'Museum Industry',
                'id' => 14,
                'filename' => 'museum_industry.png',
            ],
            [
                'name' => 'Museum Naval',
                'id' => 15,
                'filename' => 'museum_naval.png',
            ],
            [
                'name' => 'Museum Open Air',
                'id' => 16,
                'filename' => 'museum_openair.png',
            ],
            [
                'name' => 'Museum Science',
                'id' => 17,
                'filename' => 'museum_science.png',
            ],
            [
                'name' => 'Museum War',
                'id' => 18,
                'filename' => 'museum_war.png',
            ],
            [
                'name' => 'Fountain',
                'id' => 19,
                'filename' => 'fountain-3.png',
            ],
            [
                'name' => 'Statue',
                'id' => 20,
                'filename' => 'statue-2.png',
            ],
            [
                'name' => 'Wetlands',
                'id' => 21,
                'filename' => 'wetlands.png',
            ],
            [
                'name' => 'Park/Nature Area',
                'id' => 22,
                'filename' => 'forest.png',
            ],
            [
                'name' => 'Airport',
                'filename' => 'airport.png'
            ],
            [
                'name' => 'Amphitheater',
                'filename' => 'amphitheater.png'
            ],
            [
                'name' => 'Aquarium',
                'filename' => 'aquarium.png'
            ],
            [
                'name' => 'Bar',
                'filename' => 'bar.png'
            ],
            [
                'name' => 'Battlefield',
                'filename' => 'battlefield.png'
            ],
            [
                'name' => 'Boat',
                'filename' => 'boat.png'
            ],
            [
                'name' => 'Carwash',
                'filename' => 'carwash.png'
            ],
            [
                'name' => 'Cinema',
                'filename' => 'cinema.png'
            ],
            [
                'name' => 'Ferry',
                'filename' => 'ferry.png'
            ],
            [
                'name' => 'Festival',
                'filename' => 'festival.png'
            ],
            [
                'name' => 'Field',
                'filename' => 'field.png'
            ],
            [
                'name' => 'Fossils',
                'filename' => 'fossils.png'
            ],
            [
                'name' => 'Garden',
                'filename' => 'garden.png'
            ],
            [
                'name' => 'Harbor',
                'filename' => 'harbor.png'
            ],
            [
                'name' => 'Highschool',
                'filename' => 'highschool.png'
            ],
            [
                'name' => 'Information',
                'filename' => 'information.png'
            ],
            [
                'name' => 'Kayaking',
                'filename' => 'kayaking.png'
            ],
            [
                'name' => 'Landmark',
                'filename' => 'landmark.png'
            ],
            [
                'name' => 'Market',
                'filename' => 'market.png'
            ],
            [
                'name' => 'Memorial',
                'filename' => 'memorial.png'
            ],
            [
                'name' => 'Mine',
                'filename' => 'mine.png'
            ],
            [
                'name' => 'Monument',
                'filename' => 'monument.png'
            ],
            [
                'name' => 'Mural',
                'filename' => 'mural.png'
            ],
            [
                'name' => 'Observatory',
                'filename' => 'observatory.png'
            ],
            [
                'name' => 'Shipwreck',
                'filename' => 'shipwreck.png'
            ],
            [
                'name' => 'Stadium',
                'filename' => 'stadium.png'
            ],
            [
                'name' => 'Theater',
                'filename' => 'theater.png'
            ],
            [
                'name' => 'Tower',
                'filename' => 'tower.png'
            ],
            [
                'name' => 'Train',
                'filename' => 'train.png'
            ],
            [
                'name' => 'Tunnel',
                'filename' => 'tunnel.png'
            ],
            [
                'name' => 'Wetlands',
                'filename' => 'wetlands.png'
            ],
            [
                'name' => 'Zoo',
                'filename' => 'zoo.png'
            ],
            [
                'name' => 'Art Gallery',
                'filename' => 'artgallery.png'
            ],
            [
                'name' => 'Battleship',
                'filename' => 'battleship-3.png'
            ],
            [
                'name' => 'Beautiful View',
                'filename' => 'beautifulview.png'
            ],
            [
                'name' => 'Brewery',
                'filename' => 'brewery1.png'
            ],
            [
                'name' => 'City',
                'filename' => 'bigcity.png'
            ],
            [
                'name' => 'Camping',
                'filename' => 'campingcar.png'
            ],
            [
                'name' => 'Car Rental',
                'filename' => 'carrental.png'
            ],
            [
                'name' => 'Canyon',
                'filename' => 'canyon-2.png'
            ],
            [
                'name' => 'Castle',
                'filename' => 'castle-2.png'
            ],
            [
                'name' => 'Cave',
                'filename' => 'cave-2.png'
            ],
            [
                'name' => 'Church',
                'filename' => 'church-2.png'
            ],
            [
                'name' => 'Desert',
                'filename' => 'desert-2.png'
            ],
            [
                'name' => 'Ferris Wheel',
                'filename' => 'ferriswheel.png'
            ],
            [
                'name' => 'Fjord',
                'filename' => 'fjord-2.png'
            ],
            [
                'name' => 'Fountain',
                'filename' => 'fountain-3.png'
            ],
            [
                'name' => 'Geocaching',
                'filename' => 'geocaching-3.png'
            ],
            [
                'name' => 'Geothermal Site',
                'filename' => 'geothermal-site.png'
            ],
            [
                'name' => 'Geyser',
                'filename' => 'geyser-2.png'
            ],
            [
                'name' => 'Ghost Town',
                'filename' => 'ghosttown.png'
            ],
            [
                'name' => 'Glacier',
                'filename' => 'glacier-2.png'
            ],
            [
                'name' => 'Hiking',
                'filename' => 'hiking2.png'
            ],
            [
                'name' => 'Museum Historical',
                'filename' => 'historical_museum.png'
            ],
            [
                'name' => 'Hot Spring',
                'filename' => 'hotspring.png'
            ],
            [
                'name' => 'Marina',
                'filename' => 'marina-2.png'
            ],
            [
                'name' => 'Panoramic View',
                'filename' => 'panoramicview.png'
            ],
            [
                'name' => 'Theme Park',
                'filename' => 'themepark.png'
            ],
            [
                'name' => 'Windmill',
                'filename' => 'windmill-2.png'
            ],
            [
                'name' => 'Spring',
                'filename' => 'river-2.png',
            ],
            [
                'name' => 'Lighthouse',
                'filename' => 'lighthouse-2.png',
            ],
            [
                'name' => 'Ocean',
                'filename' => 'water.png',
            ]
        ])
        ->map(fn ($icon) => (object) $icon)
        ->values();
    }
}
