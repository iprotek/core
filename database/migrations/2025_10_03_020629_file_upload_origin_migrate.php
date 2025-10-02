<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        DB::update("UPDATE file_uploads SET origin_url = ? WHERE id > 0 ", [config('app.url')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
