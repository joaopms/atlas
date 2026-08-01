<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->nullable()->index();
            $table->foreignId('container_id')->nullable();
            $table->string('name');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('container_id')
                ->references('id')
                ->on('inventory_containers')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }
};
