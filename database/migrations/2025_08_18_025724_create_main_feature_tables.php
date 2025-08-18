<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Tabel Kategori Materi (Material_Categories)
        // Mengelompokkan materi ke dalam kategori yang sesuai.
        Schema::create('material_categories', function (Blueprint $table) {
            $table->id('kategori_id');
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable();
        });

        // 2. Tabel Materi (Materials)
        // Menyimpan konten pembelajaran yang bisa diakses pengguna.
        Schema::create('materials', function (Blueprint $table) {
            $table->id('materi_id');
            $table->foreignId('kategori_id')->constrained('material_categories', 'kategori_id')->onDelete('cascade');
            $table->string('judul');
            $table->text('isi_materi'); // Bisa berupa teks, link, atau path file
            $table->timestamps();
        });

        // 3. Tabel Tryout
        // Mendefinisikan setiap paket tryout yang tersedia.
        Schema::create('tryouts', function (Blueprint $table) {
            $table->id('tryout_id');
            $table->string('nama_tryout');
            $table->string('jenis_tryout');
            $table->text('deskripsi')->nullable();
            $table->integer('durasi_menit');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        // 4. Tabel Soal (Questions)
        // Pusat dari semua pertanyaan yang ada di sistem (bank soal).
        Schema::create('questions', function (Blueprint $table) {
            $table->id('soal_id');
            $table->text('isi_soal');
            $table->text('pembahasan')->nullable();
            $table->timestamps();
        });

        // 5. Tabel Pilihan Jawaban (Answer_Choices)
        // Menyimpan semua opsi jawaban untuk setiap soal.
        Schema::create('answer_choices', function (Blueprint $table) {
            $table->id('pilihan_id');
            $table->foreignId('soal_id')->constrained('questions', 'soal_id')->onDelete('cascade');
            $table->text('isi_pilihan');
            $table->boolean('is_correct')->default(false);
        });

        // 6. Tabel Hasil Tryout (Tryout_Results)
        // Mencatat setiap sesi pengerjaan tryout oleh pengguna.
        Schema::create('tryout_results', function (Blueprint $table) {
            $table->id('hasil_id');
            // FIX: Mengubah constrained() agar merujuk ke primary key 'id' di tabel 'users' secara default.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tryout_id')->constrained('tryouts', 'tryout_id')->onDelete('cascade');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->decimal('skor_akhir', 5, 2)->nullable();
            $table->enum('status', ['sedang dikerjakan', 'selesai'])->default('sedang dikerjakan');
        });

        // 7. Tabel Jawaban Pengguna (User_Answers)
        // Merekam setiap jawaban yang dipilih pengguna pada sebuah sesi tryout.
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id('jawaban_pengguna_id');
            $table->foreignId('hasil_id')->constrained('tryout_results', 'hasil_id')->onDelete('cascade');
            $table->foreignId('soal_id')->constrained('questions', 'soal_id')->onDelete('cascade');
            // Mengizinkan null jika pengguna tidak menjawab
            $table->foreignId('pilihan_id')->nullable()->constrained('answer_choices', 'pilihan_id')->onDelete('set null');
        });

        // 8. Tabel Pivot Tryout_Soal (Tryout_Question)
        // Menghubungkan Tryout dan Soal (Many-to-Many).
        Schema::create('tryout_question', function (Blueprint $table) {
            $table->primary(['tryout_id', 'soal_id']); // Composite primary key
            $table->foreignId('tryout_id')->constrained('tryouts', 'tryout_id')->onDelete('cascade');
            $table->foreignId('soal_id')->constrained('questions', 'soal_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Hapus tabel dalam urutan terbalik untuk menghindari error foreign key
        Schema::dropIfExists('tryout_question');
        Schema::dropIfExists('user_answers');
        Schema::dropIfExists('tryout_results');
        Schema::dropIfExists('answer_choices');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('tryouts');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('material_categories');
    }
};
