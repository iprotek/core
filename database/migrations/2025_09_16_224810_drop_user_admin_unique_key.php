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
        if (Schema::hasTable('user_admin_pay_accounts')) {
            $indexes = DB::select("SHOW INDEX FROM user_admin_pay_accounts WHERE Key_name = 'user_admin_pay_accounts_user_admin_id_unique'");
            if ($indexes) {
                Schema::table('user_admin_pay_accounts', function (Blueprint $table) {
                    $table->dropUnique('user_admin_pay_accounts_user_admin_id_unique');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
