<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeAnimalProfileColumnsNullable extends Migration
{
    /**
     * name/lat/lng stay required — every animals row is either a full dog
     * profile (AdminController) or a map pin (GoogleMapController), and only
     * those three columns are common to both. The rest are dog-profile-only
     * and app-level validation already enforces them where that matters.
     *
     * @return void
     */
    public function up()
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            // Non-MySQL test connections (sqlite) start from the final schema
            // already, via 2026_07_18_030000_restore_animal_profile_columns_not_null
            // — this historical MySQL-only ALTER doesn't apply there.
            return;
        }

        DB::statement('
            ALTER TABLE animals
                MODIFY species VARCHAR(255) NULL,
                MODIFY marking VARCHAR(255) NULL,
                MODIFY gender VARCHAR(255) NULL,
                MODIFY collar VARCHAR(255) NULL,
                MODIFY age VARCHAR(255) NULL,
                MODIFY status VARCHAR(255) NULL,
                MODIFY vet VARCHAR(255) NULL,
                MODIFY owner VARCHAR(255) NULL,
                MODIFY image VARCHAR(255) NULL,
                MODIFY location VARCHAR(255) NULL
        ');
    }

    /**
     * @return void
     */
    public function down()
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('
            ALTER TABLE animals
                MODIFY species VARCHAR(255) NOT NULL,
                MODIFY marking VARCHAR(255) NOT NULL,
                MODIFY gender VARCHAR(255) NOT NULL,
                MODIFY collar VARCHAR(255) NOT NULL,
                MODIFY age VARCHAR(255) NOT NULL,
                MODIFY status VARCHAR(255) NOT NULL,
                MODIFY vet VARCHAR(255) NOT NULL,
                MODIFY owner VARCHAR(255) NOT NULL,
                MODIFY image VARCHAR(255) NOT NULL,
                MODIFY location VARCHAR(255) NOT NULL
        ');
    }
}
