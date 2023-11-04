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
        Schema::create('keyword_reply_texts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('keyword_reply_id');
            $table->longText('reply');
            $table->timestamps();

            $table->foreign('keyword_reply_id')
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
        Schema::dropIfExists('keyword_reply_texts');
    }
};
