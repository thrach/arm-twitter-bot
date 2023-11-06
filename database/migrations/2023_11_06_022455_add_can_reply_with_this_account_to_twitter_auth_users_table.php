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
        Schema::table('twitter_auth_users', function (Blueprint $table) {
            $table->boolean('can_reply_with_this_account')
                ->default(true)
                ->after('twitter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('twitter_auth_users', function (Blueprint $table) {
            $table->dropColumn('can_reply_with_this_account');
        });
    }
};
