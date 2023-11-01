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
        Schema::create('keyword_reply_twitter_search_exclusion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('twitter_search_exclusion_id');
            $table->unsignedBigInteger('keyword_reply_id');
            $table->timestamps();

            $table->foreign('twitter_search_exclusion_id', 'tse_kr_tse_id_fk')
                ->references('id')
                ->on('twitter_search_exclusions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('keyword_reply_id', 'tse_kr_kr_id_fk')
                ->references('id')
                ->on('keyword_replies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keyword_reply_twitter_search_exclusion');
    }
};
