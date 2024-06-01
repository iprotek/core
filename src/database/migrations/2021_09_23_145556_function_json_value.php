<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class FunctionJsonValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::unprepared("DROP FUNCTION IF EXISTS fnJSON_VALUE");
        DB::unprepared('
        CREATE FUNCTION `fnJSON_VALUE`(`_JsonSrc` LONGTEXT,`_JsonPoint` TEXT) RETURNS text CHARSET latin1
        BEGIN
            RETURN JSON_UNQUOTE(JSON_EXTRACT(`_JsonSrc`,`_JsonPoint`));
        END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
