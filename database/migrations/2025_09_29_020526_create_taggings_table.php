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
        Schema::create('taggings', function (Blueprint $table) {
            
            $table->iprotekDefaultColumns();

            $table->string('target_id')->nullable();
            $table->string('target_name');
            $table->text('value');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggings');
    }
};
