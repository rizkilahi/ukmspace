<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'ukm_id')) {
                $table->foreignId('ukm_id')->nullable()->constrained('ukms')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'ukm_id')) {
                $table->dropForeign(['ukm_id']);
                $table->dropColumn('ukm_id');
            }
        });
    }
};
