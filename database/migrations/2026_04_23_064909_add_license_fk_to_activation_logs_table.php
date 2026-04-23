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
        Schema::table('license_activation_logs', function (Blueprint $table) {
            $table->foreign('license_id')
                ->references('id')
                ->on('licenses')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_activation_logs', function (Blueprint $table) {
            $table->dropForeign(['license_id']);
        });
    }
};
