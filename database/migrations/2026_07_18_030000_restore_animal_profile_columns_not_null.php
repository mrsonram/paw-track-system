<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RestoreAnimalProfileColumnsNotNull extends Migration
{
    /**
     * Map pins now live in map_locations, so animals only ever holds full dog
     * profiles again — restore the NOT NULL constraints that
     * 2026_07_18_000000_make_animal_profile_columns_nullable relaxed to let
     * pin-only rows share this table.
     *
     * @return void
     */
    public function up()
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            // Non-MySQL test connections (sqlite) already start NOT NULL —
            // create_animals_table never marked these columns nullable.
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
}
