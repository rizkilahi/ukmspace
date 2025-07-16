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
            $table->unsignedBigInteger('ukm_id')->nullable()->after('id'); // Menambahkan kolom ukm_id
            $table->foreign('ukm_id')->references('id')->on('ukms')->onDelete('cascade'); // Menambahkan foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ukm_id']); // Hapus foreign key
            $table->dropColumn('ukm_id');   // Hapus kolom
        });
    }
};
