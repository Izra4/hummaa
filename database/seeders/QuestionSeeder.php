<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\AnswerChoice;
use App\Models\Tryout;
use App\Models\MaterialCategory;
use App\Models\Material;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Buat tryout dan kategori materi
        $this->createTryouts();
        $this->createMaterialCategories();
        $this->createMaterials();
        $this->createQuestions();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Questions, Tryouts, and Materials seeded successfully!');
    }

    private function createTryouts()
    {
        $tryouts = [
            [
                'nama_tryout' => 'PPPK 2022 Aljabar',
                'jenis_tryout' => 'TPA',
                'deskripsi' => 'Bank soal Tes Potensi Akademik PPPK 2022 - Aljabar (100 Soal)',
                'durasi_menit' => 90,
                'is_published' => true,
            ],
            [
                'nama_tryout' => 'PPPK 2022 Silogisme',
                'jenis_tryout' => 'TPA',
                'deskripsi' => 'Bank soal Tes Potensi Akademik PPPK 2022 - Silogisme (80 Soal)',
                'durasi_menit' => 90,
                'is_published' => true,
            ],
            [
                'nama_tryout' => 'PPPK 2022 Analogi Verbal',
                'jenis_tryout' => 'TPA',
                'deskripsi' => 'Bank soal Tes Potensi Akademik PPPK 2022 - Analogi Verbal (120 Soal)',
                'durasi_menit' => 90,
                'is_published' => true,
            ],
            [
                'nama_tryout' => 'PPPK 2022 Deret Angka',
                'jenis_tryout' => 'TPA',
                'deskripsi' => 'Bank soal Tes Potensi Akademik PPPK 2022 - Deret Angka (100 Soal)',
                'durasi_menit' => 90,
                'is_published' => true,
            ],
            [
                'nama_tryout' => 'Bank Soal TKD',
                'jenis_tryout' => 'TKD',
                'deskripsi' => 'Bank soal Tes Kompetensi Dasar untuk persiapan CPNS/PPPK',
                'durasi_menit' => 120,
                'is_published' => true,
            ],
            [
                'nama_tryout' => 'Bank Soal TIU',
                'jenis_tryout' => 'TIU',
                'deskripsi' => 'Bank soal Tes Intelegensi Umum untuk persiapan CPNS/PPPK',
                'durasi_menit' => 100,
                'is_published' => true,
            ],
        ];

        foreach ($tryouts as $tryout) {
            Tryout::create($tryout);
        }
    }

    private function createMaterialCategories()
    {
        $categories = [
            ['nama_kategori' => 'Aljabar', 'deskripsi' => 'Materi aljabar untuk TPA'],
            ['nama_kategori' => 'Silogisme', 'deskripsi' => 'Materi silogisme dan logika'],
            ['nama_kategori' => 'Analogi Verbal', 'deskripsi' => 'Materi analogi verbal dan hubungan kata'],
            ['nama_kategori' => 'Deret Angka', 'deskripsi' => 'Materi deret angka dan pola matematika'],
            ['nama_kategori' => 'TWK', 'deskripsi' => 'Tes Wawasan Kebangsaan'],
            ['nama_kategori' => 'TIU', 'deskripsi' => 'Tes Intelegensi Umum'],
        ];

        foreach ($categories as $category) {
            MaterialCategory::create($category);
        }
    }

    private function createMaterials()
    {
        $materials = [
            [
                'kategori_id' => 1, // Aljabar
                'judul' => 'Pengenalan Aljabar Dasar',
                'isi_materi' => 'Aljabar adalah cabang matematika yang menggunakan huruf untuk mewakili bilangan yang tidak diketahui. Dalam tes PPPK, soal aljabar biasanya berupa persamaan linear sederhana.',
            ],
            [
                'kategori_id' => 1, // Aljabar
                'judul' => 'Persamaan Linear Satu Variabel',
                'isi_materi' => 'Persamaan linear adalah persamaan yang pangkat tertinggi variabelnya adalah 1. Contoh: 2x + 5 = 15. Untuk menyelesaikannya, pindahkan konstanta ke ruas kanan.',
            ],
            [
                'kategori_id' => 2, // Silogisme
                'judul' => 'Dasar-dasar Silogisme',
                'isi_materi' => 'Silogisme adalah bentuk penalaran logis yang terdiri dari premis mayor, premis minor, dan kesimpulan. Contoh: Semua manusia mortal (mayor), Sokrates manusia (minor), maka Sokrates mortal (kesimpulan).',
            ],
            [
                'kategori_id' => 3, // Analogi Verbal
                'judul' => 'Konsep Analogi Verbal',
                'isi_materi' => 'Analogi verbal adalah perbandingan hubungan antara dua kata. Pola umum: A : B = C : D. Contoh: BUKU : BACA = MUSIK : DENGAR. Hubungannya adalah objek dengan aktivitasnya.',
            ],
            [
                'kategori_id' => 4, // Deret Angka
                'judul' => 'Pola Deret Angka',
                'isi_materi' => 'Deret angka mengikuti pola tertentu: aritmatika (selisih tetap), geometri (rasio tetap), atau pola khusus seperti kuadrat, fibonacci. Kunci sukses adalah menemukan polanya.',
            ],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }

    private function createQuestions()
    {
        $questions = [
            // Soal Aljabar
            [
                'isi_soal' => 'Jika 3x + 5 = 20, maka nilai x adalah...',
                'pembahasan' => '3x + 5 = 20 → 3x = 15 → x = 5',
                'choices' => [
                    ['text' => '5', 'correct' => true],
                    ['text' => '3', 'correct' => false],
                    ['text' => '4', 'correct' => false],
                    ['text' => '6', 'correct' => false],
                    ['text' => '7', 'correct' => false],
                ],
                'tryout_ids' => [1],
            ],
            [
                'isi_soal' => 'Hasil dari 2(x + 3) - 4 = 8 adalah...',
                'pembahasan' => '2(x + 3) - 4 = 8 → 2x + 6 - 4 = 8 → 2x + 2 = 8 → 2x = 6 → x = 3',
                'choices' => [
                    ['text' => '3', 'correct' => true],
                    ['text' => '2', 'correct' => false],
                    ['text' => '4', 'correct' => false],
                    ['text' => '5', 'correct' => false],
                    ['text' => '6', 'correct' => false],
                ],
                'tryout_ids' => [1],
            ],

            // Soal Silogisme
            [
                'isi_soal' => 'Semua pegawai rajin. Budi adalah pegawai. Kesimpulan yang tepat adalah...',
                'pembahasan' => 'Silogisme kategorikal: Premis mayor + Premis minor = Kesimpulan valid',
                'choices' => [
                    ['text' => 'Budi rajin', 'correct' => true],
                    ['text' => 'Budi tidak rajin', 'correct' => false],
                    ['text' => 'Semua orang rajin', 'correct' => false],
                    ['text' => 'Budi mungkin rajin', 'correct' => false],
                    ['text' => 'Tidak dapat disimpulkan', 'correct' => false],
                ],
                'tryout_ids' => [2],
            ],
            [
                'isi_soal' => 'Semua dokter pintar. Beberapa orang pintar kaya. Maka...',
                'pembahasan' => 'Tidak dapat disimpulkan pasti tentang dokter yang kaya',
                'choices' => [
                    ['text' => 'Beberapa dokter mungkin kaya', 'correct' => true],
                    ['text' => 'Semua dokter kaya', 'correct' => false],
                    ['text' => 'Tidak ada dokter yang kaya', 'correct' => false],
                    ['text' => 'Semua orang kaya adalah dokter', 'correct' => false],
                    ['text' => 'Semua orang pintar adalah dokter', 'correct' => false],
                ],
                'tryout_ids' => [2],
            ],

            // Soal Analogi Verbal
            [
                'isi_soal' => 'BUKU : PERPUSTAKAAN = MOBIL : ?',
                'pembahasan' => 'Hubungan tempat penyimpanan: buku disimpan di perpustakaan, mobil disimpan di garasi',
                'choices' => [
                    ['text' => 'GARASI', 'correct' => true],
                    ['text' => 'JALAN', 'correct' => false],
                    ['text' => 'BENSIN', 'correct' => false],
                    ['text' => 'SOPIR', 'correct' => false],
                    ['text' => 'RODA', 'correct' => false],
                ],
                'tryout_ids' => [3],
            ],
            [
                'isi_soal' => 'GURU : MENGAJAR = DOKTER : ?',
                'pembahasan' => 'Hubungan profesi dengan tugas utamanya',
                'choices' => [
                    ['text' => 'MENGOBATI', 'correct' => true],
                    ['text' => 'RUMAH SAKIT', 'correct' => false],
                    ['text' => 'STETOSKOP', 'correct' => false],
                    ['text' => 'PASIEN', 'correct' => false],
                    ['text' => 'OBAT', 'correct' => false],
                ],
                'tryout_ids' => [3],
            ],

            // Soal Deret Angka
            [
                'isi_soal' => '2, 4, 8, 16, 32, ... Angka selanjutnya adalah...',
                'pembahasan' => 'Deret geometri dengan rasio 2: 32 × 2 = 64',
                'choices' => [
                    ['text' => '64', 'correct' => true],
                    ['text' => '48', 'correct' => false],
                    ['text' => '56', 'correct' => false],
                    ['text' => '40', 'correct' => false],
                    ['text' => '72', 'correct' => false],
                ],
                'tryout_ids' => [4],
            ],
            [
                'isi_soal' => '1, 4, 9, 16, 25, ... Angka selanjutnya adalah...',
                'pembahasan' => 'Deret kuadrat: 1², 2², 3², 4², 5², maka 6² = 36',
                'choices' => [
                    ['text' => '36', 'correct' => true],
                    ['text' => '30', 'correct' => false],
                    ['text' => '35', 'correct' => false],
                    ['text' => '49', 'correct' => false],
                    ['text' => '32', 'correct' => false],
                ],
                'tryout_ids' => [4],
            ],

            // Soal TKD
            [
                'isi_soal' => 'Pancasila sebagai dasar negara Indonesia ditetapkan pada tanggal...',
                'pembahasan' => 'Pancasila ditetapkan pada tanggal 18 Agustus 1945 oleh PPKI',
                'choices' => [
                    ['text' => '18 Agustus 1945', 'correct' => true],
                    ['text' => '17 Agustus 1945', 'correct' => false],
                    ['text' => '1 Juni 1945', 'correct' => false],
                    ['text' => '22 Juni 1945', 'correct' => false],
                    ['text' => '19 Agustus 1945', 'correct' => false],
                ],
                'tryout_ids' => [5],
            ],

            // Soal TIU
            [
                'isi_soal' => 'Manakah yang tidak termasuk kelompok: APEL, JERUK, MANGGA, WORTEL, PISANG',
                'pembahasan' => 'WORTEL adalah sayuran, yang lain adalah buah-buahan',
                'choices' => [
                    ['text' => 'WORTEL', 'correct' => true],
                    ['text' => 'APEL', 'correct' => false],
                    ['text' => 'JERUK', 'correct' => false],
                    ['text' => 'MANGGA', 'correct' => false],
                    ['text' => 'PISANG', 'correct' => false],
                ],
                'tryout_ids' => [6],
            ],
        ];

        foreach ($questions as $questionData) {
            $question = Question::create([
                'isi_soal' => $questionData['isi_soal'],
                'pembahasan' => $questionData['pembahasan'],
            ]);

            foreach ($questionData['choices'] as $choiceData) {
                AnswerChoice::create([
                    'soal_id' => $question->soal_id,
                    'isi_pilihan' => $choiceData['text'],
                    'is_correct' => $choiceData['correct'],
                ]);
            }

            if (!empty($questionData['tryout_ids'])) {
                $question->tryouts()->attach($questionData['tryout_ids']);
            }
        }
    }
}