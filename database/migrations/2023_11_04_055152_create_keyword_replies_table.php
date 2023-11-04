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
        Schema::create('keyword_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('search_term_id');
            $table->unsignedBigInteger('search_term_exclusion_id')->nullable();
            $table->timestamps();

            $table->foreign('search_term_id')
                ->references('id')
                ->on('search_terms')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('search_term_exclusion_id')
                ->references('id')
                ->on('search_term_exclusions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keyword_replies');
    }
};
