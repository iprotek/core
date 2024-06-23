<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        if(Schema::hasTable('message_templates')) {  
            return;
        } 

        Schema::create('message_templates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('group_id');
            $table->bigInteger('pay_created_by')->nullable(); 
            $table->bigInteger('pay_updated_by')->nullable();
            $table->bigInteger('pay_deleted_by')->nullable();
            $table->softDeletes(); 
            $table->timestamps();

            $table->string('name');
            $table->longText('content');
            $table->boolean('is_active')->default(1);
            $table->integer('message_footer_id')->nullable();
            $table->integer('message_header_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_templates');
    }
}
