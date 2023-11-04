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
        Schema::table('tweets', function (Blueprint $table) {
            $table->unsignedBigInteger('search_term_id')
                ->nullable()
                ->after('id');

            $table->foreign('search_term_id')
                ->references('id')
                ->on('search_terms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tweets', function (Blueprint $table) {
            $table->dropForeign('tweets_twitter_user_id_foreign');
            $table->dropColumn('search_term_id');
        });
    }
};
