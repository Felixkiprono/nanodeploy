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
        Schema::create('sshkeys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->text('public_key');
            $table->text('private_key');
            $table->string('fingerprint')->unique();
            $table->unsignedBigInteger('owner_user_id');
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sshkeys');
    }
};
