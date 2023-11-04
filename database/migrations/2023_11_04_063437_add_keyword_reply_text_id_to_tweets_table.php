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
            $table->unsignedBigInteger('keyword_reply_text_id')
                ->nullable()
                ->after('id');

            $table->foreign('keyword_reply_text_id')
                ->references('id')
                ->on('keyword_reply_texts')
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
            $table->dropForeign('tweets_keyword_reply_text_id_foreign');
            $table->dropColumn('keyword_reply_text_id');
        });
    }
};
