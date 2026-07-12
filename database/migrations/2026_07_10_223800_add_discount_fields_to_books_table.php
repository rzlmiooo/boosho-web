<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->integer('discount_percent')->default(0);
            $table->dateTime('discount_start')->nullable();
            $table->dateTime('discount_end')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['discount_percent', 'discount_start', 'discount_end']);
        });
    }
};
