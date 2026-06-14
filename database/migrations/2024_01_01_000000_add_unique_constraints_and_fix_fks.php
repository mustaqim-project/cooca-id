<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add Unique Constraints
        
        // Users email
        Schema::table('users', function (Blueprint $table) {
            // Hapus index unik lama jika ada dengan nama berbeda (opsional, sesuaikan nama indeks lama)
            // $table->dropUnique('users_email_unique'); 
            
            $table->unique('email', 'users_email_unique_final');
        });

        // Domains name
        Schema::table('domains', function (Blueprint $table) {
            $table->unique('name', 'domains_name_unique_final');
        });

        // Licenses key
        Schema::table('licenses', function (Blueprint $table) {
            $table->unique('key', 'licenses_key_unique_final');
        });

        // 2. Verify and Fix Foreign Keys (Contoh jika ada yang perlu diperbaiki)
        // Catatan: Laravel biasanya sudah menangani ini di migration awal.
        // Bagian ini hanya jika Anda menemukan FK yang salah definisi di migration sebelumnya.
        
        // Contoh: Memastikan erp_requests.user_id memiliki cascade delete jika belum
        // Jika FK sudah ada, kita harus drop dulu lalu create ulang (hati-hati di production)
        // Kode di bawah adalah contoh pola, sesuaikan dengan tabel nyata Anda
        
        /*
        Schema::table('erp_requests', function (Blueprint $table) {
            // Drop FK lama jika nama spesifik diketahui
            // $table->dropForeign(['user_id']);
            
            // Tambah ulang dengan cascade
            // $table->foreign('user_id')
            //       ->references('id')
            //       ->on('users')
            //       ->onDelete('cascade');
        });
        */
       
        // Untuk sekarang, kita asumsikan migration awal sudah benar mendefinisikan FK.
        // Fokus utama adalah UNIQUE constraint yang sering terlewat.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique_final');
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->dropUnique('domains_name_unique_final');
        });

        Schema::table('licenses', function (Blueprint $table) {
            $table->dropUnique('licenses_key_unique_final');
        });
    }
};
