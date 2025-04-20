<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Enums\Placement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Supports\JobPlacement;

class JobPlacementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_standardizes_import_placement()
    {
        $expectedPlacements = collect([
            'FT -FRONT 2" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT1 -FRONT 1" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FRONT 3" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FRONT' => Placement::FRONT,
            'LC -LEFT CHEST' => Placement::LEFT_CHEST,
            'RC -RIGHT CHEST' => Placement::RIGHT_CHEST,
            'LT -LEFT THIGH (SHORTS/PANTS)' => Placement::LEFT_THIGH,
            'RT -RIGHT THIGH (SHORTS/PANTS)' => Placement::RIGHT_THIGH,
            'FT -FRONT .5" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -PRINTS 1 1/2" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FRONT 4.5" DOWN FROM NECK SEAM TO TOP OF CITY NAME' => Placement::FRONT,
            'FT -FRONT - CLEAR INKS PRINT WET ON WET' => Placement::FRONT,
            'FT -FRONT 2"DOWN FROM NECK SEAM' => Placement::FRONT,
            'BK -BACK PRINT 2"DOWN FROM COLLAR' => Placement::BACK,
            'FT -CENTER OF GRAPHIC PRINTS 1/2" DOWN FROM V-NECK' => Placement::FRONT,
            'LC -LEFT CHEST 3" DOWN FROM NECK SEAM' => Placement::LEFT_CHEST,
            'FT -FRONT 3” DOWN FROM NECK SEAM' => Placement::FRONT,
            'LC -LEFT CHEST 4" DOWN FROM NECK ON CENTER' => Placement::LEFT_CHEST,
            'FT -1/2" DOWN FROM SEAM' => Placement::FRONT,
            'FT -1/2" DOWN FROM NECK SEAM' => Placement::FRONT,
            'BK -BACK PRINT 2" DOWN FROM COLLAR' => Placement::BACK,
            'FT -FRONT 1/2" DOWN FROM V NECK' => Placement::FRONT,
            'FT -2" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FRONT 1" DOWN FROM V-NECK' => Placement::FRONT,
            'FT -FRONT 1/2 DOWNN FROM SEAM' => Placement::FRONT,
            'FT .5" DOWN FROM SEAM' => Placement::FRONT,
            'FT -FRONT 2" DOWN FROM HIGH POINT OF GRAPHIC' => Placement::FRONT,
            'FT1 -FRONT 2" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FRONT 1" DOWN FROM SEAM' => Placement::FRONT,
            'FT1 -FRONT.5" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FRONT 3"DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FRONT 4" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FRONT 1/2" DOWN FROM V-NECK' => Placement::FRONT,
            'FT -FRONT 3" DOWN FROM NECK SEAM TO TOP OF ART' => Placement::FRONT,
            'FT PRINTS 1/2" DOWN FROM V-NECK' => Placement::FRONT,
            'LSS -6" DOWN FROM HPS' => Placement::LEFT_SHORT_SLEEVE,
            'FT -FRONT 1" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FRONT 1" DOWN FROM V-NECK TO CENTER OF GRAPHIC' => Placement::FRONT,
            'FT -FRONT .5" DOWN FROM V NECK SEAM' => Placement::FRONT,
            'FT -.5" DOWN FROM NECK SEAM' => Placement::FRONT,
            'LC -LEFT CHEST 2" DOWN FROM NECK SEAM' => Placement::LEFT_CHEST,
            'BK -BACK 2" DOWN FROM COLLAR' => Placement::BACK,
            'FT -CENTER OF GRAPHIC PRINTS 1/2" BELOW V-NECK' => Placement::FRONT,
            'FT1 - 1" DOWN FROM SEAM' => Placement::FRONT,
            'FT -FRONT 1/2" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FRONT 1.5" DOWN' => Placement::FRONT,
            'FT -FRONT /�' => Placement::FRONT,
            'FT1 -FRONT 1" DOWN FROM V NECK SEAM' => Placement::FRONT,
            'TWINS MEMORIAL DAY MENS' => Placement::UNKNOWN,
            'LLS -LONG LEFT SLEEVE 5" FROM SHOULDER SEAM' => Placement::LEFT_LONG_SLEEVE,
            'LLS-LONG LEFT SLEEVE 5" FROM SHOULDER SEAM' => Placement::LEFT_LONG_SLEEVE,
            'RLS-LONG RIGHT SLEEVE 5" FROM SHOULDER SEAM' => Placement::RIGHT_LONG_SLEEVE,
            'FT -1.5" DOWN FROM SEAM' => Placement::FRONT,
            'FT -3/4" DOWN FROM V-NECK' => Placement::FRONT,
            'BK -BACK PRINT 3" DOWN FROM COLLAR' => Placement::BACK,
            '`T1 -FRONT 1" DOWN FROM NECK SEAM MARK LOCK UPS' => Placement::UNKNOWN,
            'FT -FRONT 1.5" FROM CENTER OF NECK SEAM' => Placement::FRONT,
            'BK1 -BACK 1" DOWN FROM COLLAR' => Placement::BACK,
            'FT -FRONT 1.5" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -1" DOWN FROM SEAM' => Placement::FRONT,
            'FT -FRONT 2" DOWN' => Placement::FRONT,
            'FT -FRONT 2" DOWN FROM COLLAR' => Placement::FRONT,
            'LC -LEFT CHEST TOP OF ART PRINTS 6" FROM HPS' => Placement::LEFT_CHEST,
            'FT -FRONT 3.5" DOWN TO TOP OF ARTWORK' => Placement::FRONT,
            'FT -2" DOWN FROM SEAM' => Placement::FRONT,
            'FPSE-FRONT PLCKT/SHOULDERS/SLV ENDS' => Placement::UNKNOWN,
            'LC -LEFT CHEST 3" DOWN FROM NECK ON CENTER' => Placement::LEFT_CHEST,
            'FT -FRONT 3" DOWN FROM COLLAR' => Placement::FRONT,
            '-LC - LEFT CHEAT' => Placement::LEFT_CHEST,
            'FT -3/4" DOWN FROM SEAM' => Placement::FRONT,
            'FT -1/2" DOWN FROM NECK' => Placement::FRONT,
            'LC -LEFT CHEST 3" DOWN CENTERED' => Placement::LEFT_CHEST,
            'LS -LEFT SLEEVE 1.5" UP FROM SEAM' => Placement::LEFT_SLEEVE,
            'FT -FRONT 1.5" DOWN FROM TOP OF ART' => Placement::FRONT,
            'FT -1/2"DOWN FROM SEAM' => Placement::FRONT,
            'FT -FT -1/2" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT1 -1" DOWN FROM NECK SEAM' => Placement::FRONT,
            'FT -FT .5" DOWN FROM NECK SEAM' => Placement::FRONT,
            'BK BACK PRINT' => Placement::BACK,
            'BK -BACK PRINT' => Placement::BACK,
            'BK -BACK PRINT 1" DOWN FROM COLLAR' => Placement::BACK,
            'BK1 -BACK PRINT 1" DOWN FROM NECK SEAM' => Placement::BACK,
            'BK1 -BACK PRINT 1" DOWN FROM COLLAR' => Placement::BACK,
            'BK -BACK PRINT 2.5" FROM NECK SEAM' => Placement::BACK,
            'BK -BACK PRINT 2" DOWN FROM NECK SEAM' => Placement::BACK,
            'BK -BACK PRINT 3" DOWN FROM NECK SEAM' => Placement::BACK,
            'LLS -LEFT LONG SLEEVE' => Placement::LEFT_LONG_SLEEVE,
            'CSTM-FOIL' => Placement::FOIL,
            'FT -FOIL' => Placement::FOIL,
            'CSTM-CUSTOM PLACEMENT' => Placement::CUSTOM,
            'BK -BACK PRINT 3: DOWN FROM COLLAR' => Placement::BACK,
            'BK -BACK 3" DOWN FROM COLLAR' => Placement::BACK,
            'BK - BACK 3" DOWN FROM COLLA' => Placement::BACK,
            'LLS -1" UP FROM CUFF' => Placement::LEFT_LONG_SLEEVE,
            'LSS -LEFT SHORT SLEEVE HEADWEAR MARK LOCKUPS' => Placement::LEFT_SHORT_SLEEVE,
            'LSS -LEFT SHORT SLEEVE' => Placement::LEFT_SHORT_SLEEVE,
            'BK -BACK PRINT 2.5" DOWN FROM COLLAR' => Placement::BACK,
            'LSS -LEFT SHORT SLEEVE 1.5" UP FROM SEAM' => Placement::LEFT_SHORT_SLEEVE,
            'FOIL' => Placement::FOIL,
            'LS -LEFT SLEEVE' => Placement::LEFT_SLEEVE,
            'RS -RIGHT SLEEVE' => Placement::RIGHT_SLEEVE,
            'LSS -LEFT SHOR SLEEVE' => Placement::LEFT_SHORT_SLEEVE,
            'RSS -RIGHT SHOR SLEEVE' => Placement::RIGHT_SHORT_SLEEVE,
            'BK -BACK PRINT 3"DOWN FROM COLLAR' => Placement::BACK,
        ]);

        // Make sure we are testing all of the possible placements. Just in case...
        $this->assertEquals(
            collect(Placement::keys())->sort()->values()->toArray(),
            $expectedPlacements->unique()->sort()->values()->toArray()
        );

        $expectedPlacements->each(function ($placement, $importValue) {
            $this->assertEquals(
                $placement,
                (new JobPlacement)->convertFromImportValue($importValue)
            );
        });
    }
}
