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
        Schema::create('tweets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('keyword_reply_id');
            $table->unsignedBigInteger('twitter_user_id')->nullable();
            $table->unsignedBigInteger('tweet_id');
            $table->text('tweet');
            $table->text('reply')->nullable();
            $table->boolean('replied')->default(false);
            $table->timestamps();

            $table->foreign('keyword_reply_id')
                ->references('id')
                ->on('keyword_replies')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('twitter_user_id')
                ->references('id')
                ->on('twitter_users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweets');
    }
};
