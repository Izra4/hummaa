<?php

namespace Database\Seeders;

use App\Models\Discussion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscussionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discussionSeeder = [
            [
                'title' => 'Soal X dan Z gimana caranya yaa?',
                'image' => 'discussion-photos/img.png',
                'user_id' => 1,
                'desc' => "Ini kan aku udah itu, kok gini?"
            ],
            [
                'title' => 'Soal ini gmn bjir?',
                'image' => 'discussion-photos/img.png',
                'user_id' => 1,
                'desc' => "Sumpek banget anjay, ga bisa bisa?"
            ],
            [
                'title' => 'Soal Bahasa Vanuatu?',
                'image' => 'discussion-photos/img.png',
                'user_id' => 1,
                'desc' => "Ini grammarnya udah bener belum yaa"
            ]
        ];

        foreach ($discussionSeeder as $item) {
            Discussion::create($item);
        }
    }
}
