<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_containers', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->nullable()->index();
            $table->string('name');
            $table->foreignId('location_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('location_id')
                ->references('id')
                ->on('inventory_locations')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }
};
