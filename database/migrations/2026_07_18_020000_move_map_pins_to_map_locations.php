<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MoveMapPinsToMapLocations extends Migration
{
    /**
     * animals rows with no status were map pins (added via GoogleMapController,
     * which only ever collected name/lat/lng) sharing the animals table before
     * map_locations existed. Move them over, then the next migration restores
     * animals' profile columns to NOT NULL now that pins live elsewhere.
     *
     * @return void
     */
    public function up()
    {
        $pins = DB::table('animals')->whereNull('status')->get(['name', 'lat', 'lng']);

        foreach ($pins as $pin) {
            DB::table('map_locations')->insert([
                'name' => $pin->name,
                'lat' => $pin->lat,
                'lng' => $pin->lng,
            ]);
        }

        DB::table('animals')->whereNull('status')->delete();
    }

    /**
     * Data migrations don't reverse cleanly — the animals rows these came
     * from are gone. Nothing to undo here.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
