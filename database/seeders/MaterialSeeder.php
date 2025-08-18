<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialCategory;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan kategori sudah ada
        $categories = MaterialCategory::all()->keyBy('nama_kategori');

        if ($categories->isEmpty()) {
            $this->command->warn('Material categories not found. Please run QuestionSeeder first.');
            return;
        }

        $materials = [
            // Materi Aljabar
            [
                'kategori' => 'Aljabar',
                'judul' => 'Pengenalan Aljabar Dasar',
                'isi_materi' => 'Aljabar adalah cabang matematika yang menggunakan huruf untuk mewakili bilangan yang tidak diketahui...',
            ],
            [
                'kategori' => 'Aljabar',
                'judul' => 'Operasi Aljabar',
                'isi_materi' => 'Operasi aljabar meliputi penjumlahan, pengurangan, perkalian, dan pembagian pada bentuk aljabar...',
            ],
            [
                'kategori' => 'Aljabar',
                'judul' => 'Persamaan Linear',
                'isi_materi' => 'Persamaan linear adalah persamaan yang pangkat tertinggi variabelnya adalah 1...',
            ],

            // Materi Silogisme
            [
                'kategori' => 'Silogisme',
                'judul' => 'Dasar-dasar Silogisme',
                'isi_materi' => 'Silogisme adalah bentuk penalaran logis yang terdiri dari dua premis dan satu kesimpulan...',
            ],
            [
                'kategori' => 'Silogisme',
                'judul' => 'Jenis-jenis Silogisme',
                'isi_materi' => 'Silogisme kategorikal, silogisme hipotetikal, dan silogisme disjungtif...',
            ],
            [
                'kategori' => 'Silogisme',
                'judul' => 'Aturan Silogisme yang Valid',
                'isi_materi' => 'Untuk menghasilkan kesimpulan yang valid, silogisme harus memenuhi aturan-aturan tertentu...',
            ],

            // Materi Analogi Verbal
            [
                'kategori' => 'Analogi Verbal',
                'judul' => 'Konsep Analogi Verbal',
                'isi_materi' => 'Analogi verbal adalah perbandingan hubungan antara dua kata atau konsep...',
            ],
            [
                'kategori' => 'Analogi Verbal',
                'judul' => 'Jenis Hubungan dalam Analogi',
                'isi_materi' => 'Hubungan sinonim, antonim, sebab-akibat, bagian-keseluruhan, fungsi, dll...',
            ],
            [
                'kategori' => 'Analogi Verbal',
                'judul' => 'Tips Menyelesaikan Soal Analogi',
                'isi_materi' => 'Strategi dan tips untuk menyelesaikan soal analogi verbal dengan tepat...',
            ],

            // Materi Deret Angka
            [
                'kategori' => 'Deret Angka',
                'judul' => 'Pengenalan Deret Angka',
                'isi_materi' => 'Deret angka adalah urutan bilangan yang mengikuti pola tertentu...',
            ],
            [
                'kategori' => 'Deret Angka',
                'judul' => 'Deret Aritmetika',
                'isi_materi' => 'Deret aritmetika memiliki selisih yang sama antara suku berurutan...',
            ],
            [
                'kategori' => 'Deret Angka',
                'judul' => 'Deret Geometri',
                'isi_materi' => 'Deret geometri memiliki rasio yang sama antara suku berurutan...',
            ],
            [
                'kategori' => 'Deret Angka',
                'judul' => 'Pola Deret Khusus',
                'isi_materi' => 'Deret kuadrat, kubik, fibonacci, dan pola-pola khusus lainnya...',
            ],

            // Materi TWK
            [
                'kategori' => 'TWK',
                'judul' => 'Pancasila sebagai Dasar Negara',
                'isi_materi' => 'Sejarah lahirnya Pancasila dan implementasinya sebagai dasar negara Indonesia...',
            ],
            [
                'kategori' => 'TWK',
                'judul' => 'UUD 1945',
                'isi_materi' => 'Struktur dan isi UUD 1945 beserta amandemen-amandemennya...',
            ],
            [
                'kategori' => 'TWK',
                'judul' => 'Bhinneka Tunggal Ika',
                'isi_materi' => 'Makna dan implementasi semboyan Bhinneka Tunggal Ika dalam kehidupan berbangsa...',
            ],

            // Materi TIU
            [
                'kategori' => 'TIU',
                'judul' => 'Logika dan Penalaran',
                'isi_materi' => 'Dasar-dasar logika dan penalaran untuk mengerjakan soal TIU...',
            ],
            [
                'kategori' => 'TIU',
                'judul' => 'Kemampuan Verbal',
                'isi_materi' => 'Tes kemampuan verbal meliputi sinonim, antonim, dan analogi kata...',
            ],
            [
                'kategori' => 'TIU',
                'judul' => 'Kemampuan Numerik',
                'isi_materi' => 'Tes kemampuan numerik meliputi operasi hitung dan deret angka...',
            ],
        ];

        foreach ($materials as $materialData) {
            $category = $categories->get($materialData['kategori']);
            
            if ($category) {
                Material::create([
                    'kategori_id' => $category->kategori_id,
                    'judul' => $materialData['judul'],
                    'isi_materi' => $materialData['isi_materi'],
                ]);
            }
        }

        $this->command->info('Materials seeded successfully!');
    }
}