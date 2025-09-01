<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\QuestionBankCategory;
use App\Models\QuestionType;
use App\Models\Tryout;
use App\Models\Question;
use App\Models\Option;
use App\Models\TryoutQuestion;
use Carbon\Carbon;

class DummyTryoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'peserta@gmail.com'],
            [
                'name' => 'Peserta Tryout',
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt('password')
            ]
        );

        $this->command->info('Membuat data simulasi tryout...');

        // 1. Buat Kategori & Tipe Soal
        $qbc_tpa = QuestionBankCategory::firstOrCreate(['name' => 'Tes Potensi Akademik']);
        $qt_pg = QuestionType::create(['type' => 'Pilihan Ganda']);
        $qt_isian = QuestionType::create(['type' => 'Isian Singkat']);

        // 2. Buat Paket Tryout
        $tryout = Tryout::create([
            'title' => 'Tryout Simulasi TPA #1',
            'duration_minutes' => 60,
            'year' => 2025,
            'category_id' => 1,
        ]);

        // 3. Buat 50 Soal Dummy
        for ($i = 1; $i <= 50; $i++) {
            $isPilihanGanda = ($i % 5 != 0); // Buat 1 soal isian setiap 5 soal

            if ($isPilihanGanda) {
                // Buat Soal Pilihan Ganda
                $question = Question::create([
                    'category_id' => $qbc_tpa->id,
                    'question_type_id' => $qt_pg->id,
                    'question_text' => "Ini adalah isi dari pertanyaan pilihan ganda nomor {$i}. Konsep apa yang diuji pada soal ini?",
                    'explanation' => "Ini adalah pembahasan untuk soal nomor {$i}. Kunci jawabannya adalah B karena alasan logis.",
                    'image_url' => ($i == 3) ? 'images/contoh-soal-gambar.jpg' : null, // Contoh gambar di soal no 3
                ]);

                // Buat Opsi Jawabannya
                Option::create(['question_id' => $question->question_id, 'option_text' => "Pilihan A untuk soal {$i}", 'is_correct' => false]);
                Option::create(['question_id' => $question->question_id, 'option_text' => "Pilihan B untuk soal {$i}", 'is_correct' => true]);
                Option::create(['question_id' => $question->question_id, 'option_text' => "Pilihan C untuk soal {$i}", 'is_correct' => false]);
                Option::create(['question_id' => $question->question_id, 'option_text' => "Pilihan D untuk soal {$i}", 'is_correct' => false]);
            } else {
                // Buat Soal Isian
                $question = Question::create([
                    'category_id' => $qbc_tpa->id,
                    'question_type_id' => $qt_isian->id,
                    'question_text' => "Ini adalah isi dari pertanyaan isian singkat nomor {$i}. Siapakah penemu bola lampu?",
                    'explanation' => "Pembahasan soal isian nomor {$i}: Penemu bola lampu adalah Thomas Alva Edison.",
                    'correct_answer_text' => 'Thomas Alva Edison'
                ]);
            }

            // 4. Hubungkan soal ke paket tryout
            TryoutQuestion::create([
                'tryout_id' => $tryout->tryout_id,
                'question_id' => $question->question_id,
                'question_number' => $i,
            ]);
        }

        $this->command->info('Seeder simulasi tryout selesai!');
    }
}
