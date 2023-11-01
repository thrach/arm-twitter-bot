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
        Schema::table('tweet_replies', function (Blueprint $table) {
            $table->unsignedInteger('retweet_count')->after('twitter_post_id')->default(0);
            $table->unsignedInteger('reply_count')->after('twitter_post_id')->default(0);
            $table->unsignedInteger('like_count')->after('twitter_post_id')->default(0);
            $table->unsignedInteger('quote_count')->after('twitter_post_id')->default(0);
            $table->unsignedInteger('bookmark_count')->after('twitter_post_id')->default(0);
            $table->unsignedInteger('impression_count')->after('twitter_post_id')->default(0);
            $table->timestamp('last_synced_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tweet_replies', function (Blueprint $table) {
            $table->dropColumn([
                'retweet_count',
                'reply_count',
                'like_count',
                'quote_count',
                'bookmark_count',
                'impression_count',
                'last_synced_at'
            ]);
        });
    }
};
