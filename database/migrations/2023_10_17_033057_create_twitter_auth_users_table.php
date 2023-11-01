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
        Schema::create('twitter_auth_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('oauth_credential_id');
            $table->string('twitter_id');
            $table->string('username');
            $table->string('name');
            $table->timestamps();

            $table->foreign('oauth_credential_id')
                ->references('id')
                ->on('oauth_credentials')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twitter_auth_users');
    }
};
