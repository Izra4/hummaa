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
            // Ubah avatar field menjadi text untuk menyimpan public_id yang panjang
            $table->text('avatar')->nullable()->change();
            
            // Tambah index untuk performa jika diperlukan
            $table->index('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->change();
            $table->dropIndex(['avatar']);
        });
    }
};