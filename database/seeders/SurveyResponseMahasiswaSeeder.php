<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SurveyResponseMahasiswaSeeder extends Seeder
{
    public function run()
    {
        DB::table('survey_responses')->where('survey_type_id', 4)->delete();

        $dosenQuestions = DB::table('survey_questions')
            ->where('survey_type_id', 4)
            ->where('question_type', 'rating')
            ->get();

        $numberOfRespondents = 326;
        $totalQuestions = $dosenQuestions->count();

        for ($i = 0; $i < $numberOfRespondents; $i++) {
            $formData = [
                'program_studi' => 'Teknik Informatika',
                'tahun_masuk' => (string)Arr::random([2021, 2022, 2023, 2024]),
            ];

            $questionAnswers = [];

            // Tentukan jenis responden dengan probabilitas yang realistis
            $respondentType = $this->determineRespondentType();

            // Pilih beberapa pertanyaan acak untuk rating rendah (hanya untuk responden negatif)
            $lowRatingQuestions = $this->selectLowRatingQuestions($dosenQuestions, $respondentType);

            foreach ($dosenQuestions as $question) {
                $rating = $this->assignRating($question->id, $lowRatingQuestions, $respondentType);
                $questionAnswers[(string)$question->id] = (string)$rating;
            }

            $submittedAt = Carbon::now()
                ->subDays(rand(0, 10))
                ->subHours(rand(0, 23))
                ->subMinutes(rand(0, 59));

            $ipAddress = '192.168.' . rand(0, 255) . '.' . rand(0, 255);
            $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/' . rand(80, 100) . '.0.0.0 Safari/537.36';

            DB::table('survey_responses')->insert([
                'survey_type_id' => 4,
                'survey_section_id' => null,
                'respondent_id' => null,
                'form_data' => json_encode($formData),
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

    /**
     * Menentukan tipe responden berdasarkan distribusi realistis
     */
    private function determineRespondentType()
    {
        $rand = rand(1, 100);

        if ($rand <= 1) {
            return 'very_negative'; // 1% - sangat sedikit responden (rating 1)
        } elseif ($rand <= 3) {
            return 'negative'; // 2% - sedikit responden (rating 2)
        } elseif ($rand <= 35) {
            return 'neutral'; // 32% - responden netral (rating 3)
        } else {
            return 'positive'; // 65% - mayoritas responden (rating 4)
        }
    }

    /**
     * Memilih pertanyaan yang akan mendapat rating rendah
     */
    private function selectLowRatingQuestions($questions, $respondentType)
    {
        $lowRatingQuestions = [
            'rating_1' => [],
            'rating_2' => []
        ];

        if ($respondentType === 'very_negative') {
            // Pilih 1-3 pertanyaan acak untuk rating 1 (sangat jarang)
            $selectedQuestions = $questions->random(rand(1, 3));
            $lowRatingQuestions['rating_1'] = $selectedQuestions->pluck('id')->toArray();
        } elseif ($respondentType === 'negative') {
            // Pilih 1-4 pertanyaan acak untuk rating 2 (jarang)
            $selectedQuestions = $questions->random(rand(1, 4));
            $lowRatingQuestions['rating_2'] = $selectedQuestions->pluck('id')->toArray();
        }

        return $lowRatingQuestions;
    }

    /**
     * Menentukan rating untuk setiap pertanyaan berdasarkan tipe responden
     */
    private function assignRating($questionId, $lowRatingQuestions, $respondentType)
    {
        // Jika pertanyaan ini dipilih untuk rating rendah
        if (in_array($questionId, $lowRatingQuestions['rating_1'])) {
            return 1;
        }

        if (in_array($questionId, $lowRatingQuestions['rating_2'])) {
            return 2;
        }

        // Distribusi rating berdasarkan tipe responden
        switch ($respondentType) {
            case 'very_negative':
                // Responden sangat negatif: mayoritas rating 3-4, sangat jarang rating 1
                $weights = [3 => 50, 4 => 50];
                break;

            case 'negative':
                // Responden negatif: mayoritas rating 3-4, jarang rating 2
                $weights = [3 => 55, 4 => 45];
                break;

            case 'neutral':
                // Responden netral: lebih banyak rating 3, sedikit rating 4
                $weights = [3 => 75, 4 => 25];
                break;

            case 'positive':
            default:
                // Responden positif: mayoritas rating 4, sedikit rating 3
                $weights = [3 => 15, 4 => 85];
                break;
        }

        return $this->getWeightedRandom($weights);
    }

    /**
     * Memilih rating berdasarkan bobot probabilitas
     */
    private function getWeightedRandom($weights)
    {
        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $rating => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $rating;
            }
        }

        // Fallback ke rating tertinggi jika ada masalah
        return array_key_last($weights);
    }
}
