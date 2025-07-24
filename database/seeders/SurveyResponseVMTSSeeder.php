<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SurveyTypes;
use App\Models\SurveyQuestions;
use App\Models\SurveyResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SurveyResponseVMTSSeeder extends Seeder
{
   public function run(): void
   {
      DB::beginTransaction();
      try {
         $surveyType = SurveyTypes::where('name', 'VMTSTIF')->first();
         if (!$surveyType) {
            $this->command->error('Survey type VMTS tidak ditemukan!');
            return;
         }
         $questions = SurveyQuestions::where('survey_type_id', $surveyType->id)->get();
         if ($questions->isEmpty()) {
            $this->command->error('Pertanyaan VMTS tidak ditemukan!');
            return;
         }

         // Jumlah responden per kategori
         $jumlahDosen = 18;
         $jumlahTendik = 9;
         $jumlahMahasiswa = 325;

         // 1. Dosen
         for ($i = 0; $i < $jumlahDosen; $i++) {
            $answers = [];
            foreach ($questions as $q) {
               // 80% nilai 4, 20% nilai 3
               $answers[$q->id] = ($i < $jumlahDosen * 0.8) ? 4 : 3;
            }
            SurveyResponses::create([
               'survey_type_id' => $surveyType->id,
               'respondent_category' => 'dosen',
               'submitted_at' => now()->subDays(rand(0, 10)),
               'question_answers' => json_encode($answers),
               'form_data' => json_encode([]),
               'ip_address' => '192.168.1.' . rand(1, 254),
            ]);
         }

         // 2. Tendik
         for ($i = 0; $i < $jumlahTendik; $i++) {
            $answers = [];
            foreach ($questions as $q) {
               // 90% nilai 4, 10% nilai 3
               $answers[$q->id] = ($i < $jumlahTendik * 0.9) ? 4 : 3;
            }
            SurveyResponses::create([
               'survey_type_id' => $surveyType->id,
               'respondent_category' => 'tendik',
               'submitted_at' => now()->subDays(rand(0, 10)),
               'question_answers' => json_encode($answers),
               'form_data' => json_encode([]),
               'ip_address' => '192.168.2.' . rand(1, 254),
            ]);
         }

         // 3. Mahasiswa
         $maxSpecial = 8; // Maksimal 15 orang yang punya nilai 1/2
         $specialMahasiswa = array_rand(range(0, $jumlahMahasiswa - 1), $maxSpecial);
         if (!is_array($specialMahasiswa)) $specialMahasiswa = [$specialMahasiswa];
         for ($i = 0; $i < $jumlahMahasiswa; $i++) {
            $answers = [];
            foreach ($questions as $q) {
               // Mayoritas 4, sebagian 3
               if (in_array($i, $specialMahasiswa) && rand(0, 1)) {
                  // Untuk sebagian kecil, isi 1 atau 2 pada beberapa pertanyaan saja
                  $answers[$q->id] = rand(0, 1) ? 1 : 2;
               } else {
                  $answers[$q->id] = ($i < $jumlahMahasiswa * 0.7) ? 4 : 3;
               }
            }
            SurveyResponses::create([
               'survey_type_id' => $surveyType->id,
               'respondent_category' => 'mahasiswa',
               'submitted_at' => now()->subDays(rand(0, 365)),
               'question_answers' => json_encode($answers),
               'form_data' => json_encode([]),
               'ip_address' => '192.168.3.' . rand(1, 254),
            ]);
         }

         DB::commit();
         $this->command->info('Seeder SurveyResponseVMTSSeeder selesai!');
      } catch (\Exception $e) {
         DB::rollBack();
         $this->command->error('Seeder gagal: ' . $e->getMessage());
      }
   }
}
