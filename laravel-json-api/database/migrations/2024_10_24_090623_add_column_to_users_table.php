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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable(); 
            $table->string('city')->nullable(); 
            $table->string('address')->nullable(); 
            $table->string('pin')->nullable(); 
            $table->string('bank')->nullable(); 
            $table->string('iban')->nullable(); 
            $table->string('bic')->nullable(); 
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
