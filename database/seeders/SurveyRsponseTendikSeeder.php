<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveyRsponseTendikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Hapus data lama
        DB::table('survey_responses')->where('survey_type_id', 3)->delete();

        $dosenQuestions = DB::table('survey_questions')
            ->where('survey_type_id', 3)
            ->where('question_type', 'rating')
            ->get();

        $numberOfRespondents = 9;

        for ($i = 0; $i < $numberOfRespondents; $i++) {
            $questionAnswers = [];

            foreach ($dosenQuestions as $question) {
                // 70% kemungkinan memilih 4, 30% memilih 3
                $randomNumber = rand(1, 100);
                $rating = $randomNumber <= 75 ? 4 : 3;

                $questionAnswers[(string)$question->id] = (string)$rating;
            }

            $submittedAt = Carbon::now()
                ->subDays(rand(0, 10))
                ->subHours(rand(0, 23))
                ->subMinutes(rand(0, 59));

            $ipAddress = '192.168.' . rand(0, 255) . '.' . rand(0, 255);
            $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/' . rand(80, 100) . '.0.0.0 Safari/537.36';

            DB::table('survey_responses')->insert([
                'survey_type_id' => 3,
                'survey_section_id' => null,
                'respondent_id' => null,
                'form_data' => json_encode([]),
                'respondent_category' => null,
                'question_answers' => json_encode($questionAnswers),
                'submitted_at' => $submittedAt,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
