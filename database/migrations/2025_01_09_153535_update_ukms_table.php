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
        Schema::table('ukms', function (Blueprint $table) {
            // Tambahkan kolom baru
            $table->string('address')->nullable()->after('description');
            $table->string('phone', 15)->nullable()->after('email');
            $table->string('website')->nullable()->after('phone');
            $table->date('established_date')->nullable()->after('website');

            // Modifikasi kolom yang sudah ada (opsional)
            $table->enum('verification_status', ['active', 'inactive', 'pending'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ukms', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['address', 'phone', 'website', 'established_date']);

            // Kembalikan perubahan pada kolom yang dimodifikasi (opsional)
            $table->enum('verification_status', ['active', 'inactive'])->default('active')->change();
        });
    }
};
