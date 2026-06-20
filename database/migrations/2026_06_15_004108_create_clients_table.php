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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("first_name");
            $table->string("paternal_name");
            $table->string("maternal_name");
            //$table->string("email")->unique();
            $table->string("phone")->unique();
            $table->string("rut")->unique();
            //$table->string("password");
            $table->string("street");
            $table->string("street_number");
            $table->string("apartment_number")->nullable();
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
