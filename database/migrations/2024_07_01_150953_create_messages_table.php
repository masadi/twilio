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
        Schema::create('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->sting('title');
            $table->text('body');
            $table->sting('MediaUrl')->nullable();
            $table->timestamps();
            $table->primary('id');
			$table->foreign('message_id')->references('id')->on('messages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
