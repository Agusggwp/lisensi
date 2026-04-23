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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('license_key')->unique();
            $table->text('encrypted_token')->nullable();
            $table->string('domain')->index();
            $table->string('ip_lock')->nullable();
            $table->enum('status', ['active', 'expired', 'suspended'])->default('active')->index();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('last_validated_at')->nullable();
            $table->timestamp('last_reminder_at')->nullable();
            $table->boolean('is_domain_locked')->default(true);
            $table->boolean('is_ip_locked')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
