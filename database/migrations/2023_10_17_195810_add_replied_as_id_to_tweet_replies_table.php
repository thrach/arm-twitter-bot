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
            $table->unsignedBigInteger('replied_as_id')
                ->nullable()
                ->after('tweet_id');

            $table->foreign('replied_as_id')
                ->references('id')
                ->on('tweet_replies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tweet_replies', function (Blueprint $table) {
            $table->dropForeign('tweet_replies_replied_as_id_foreign');
            $table->dropColumn('replied_as_id');
        });
    }
};
