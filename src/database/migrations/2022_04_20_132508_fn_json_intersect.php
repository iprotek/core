<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FnJsonIntersect extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::unprepared("DROP FUNCTION IF EXISTS fnJSON_INTERSECT");

        DB::unprepared("
CREATE FUNCTION `fnJSON_INTERSECT`(Array1 VARCHAR(1024), Array2 VARCHAR(1024)) RETURNS varchar(1024) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci
BEGIN
    DECLARE x int;
    DECLARE val, output varchar(1024);
    SET output = '[]';
    SET x = 0;
    IF JSON_LENGTH(Array2) < JSON_LENGTH(Array1) THEN
        SET val = Array2;
        SET Array2 = Array1;
        SET Array1 = val;
    END IF;
    WHILE x < JSON_LENGTH(Array1) DO
        SET val = JSON_EXTRACT(Array1, CONCAT('$[',x,']'));
        IF JSON_CONTAINS(LOWER(Array2), LOWER(val)) AND JSON_CONTAINS(LOWER(output), LOWER(val)) = 0 THEN
            SET output = JSON_MERGE(output,val);
        END IF;
        SET x = x + 1; 
    END WHILE;
    #IF JSON_LENGTH(output) = 0 THEN
        RETURN output;
    #ELSE
    #    RETURN output;
    #END IF;
END
        ");
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
