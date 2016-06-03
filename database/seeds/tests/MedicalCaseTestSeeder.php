<?php

use Illuminate\Database\Seeder;

class MedicalCaseTestSeeder extends Seeder {

    /**
     * 
     *
     * @return void
     */
    public function run() {
        $faker = Faker\Factory::create();

        $insertValues = [];
        for ($i = 0; $i < 100; $i++) {
            $insertValues[] = [
                'serial_num' => sprintf("MCN-%s%s", strtotime('now'), mt_rand(1, 100)),
                'created_at' => $faker->dateTimeThisDecade
            ];

            if (count($insertValues) > 100) {
                DB::table('medical_cases')
                        ->insert($insertValues);
                $insertValues = [];
            }
        }

        //seed departments of medical case
        DB::table('medical_cases')
                ->chunk(10000, function($medicalCases) use($faker) {
                    $insertData = [];
                    foreach ($medicalCases as $md) {
                        $depts = DB::table('departments')
                                ->whereBetween('created_at', [$faker->dateTimeThisDecade, $faker->dateTimeThisDecade])
                                ->limit(mt_rand(3, 7))
                                ->get();

                        foreach ($depts as $dept) {
                            $insertData[] = [
                                'medical_case_id' => $md->id,
                                'department_id' => $dept->id,
                            ];
                        }
                    }
                    DB::table('medical_case_departments')
                    ->insert($insertData);
                    unset($insertData);
                });

        //seed diagnoses of medical case
        DB::table('medical_cases')
                ->chunk(3000, function($medicalCases) use($faker) {
                    $insertData = [];
                    $diagnoses = DB::table('diagnoses')
                            ->whereBetween('created_at', [$faker->dateTimeThisDecade->format("Y-m-d H:i:s"), $faker->dateTimeThisDecade->format("Y-m-d H:i:s")])
                            ->limit(mt_rand(3, 10))
                            ->get();

                    foreach ($medicalCases as $md) {
                        foreach ($diagnoses as $dg) {
                            $insertData[] = [
                                'medical_case_id' => $md->id,
                                'diagnosis_id' => $dg->id,
                            ];
                        }
                    }

                    DB::table('medical_case_diagnoses')
                    ->insert($insertData);
                    unset($insertData);
                });

        DB::table('medical_cases')
                ->chunk(3000, function($medicalCases) use($faker) {
                    $insertData = [];
                    $patients = DB::table('patients')
                            ->whereBetween('created_at', [$faker->dateTimeThisDecade->format("Y-m-d H:i:s"), $faker->dateTimeThisDecade->format("Y-m-d H:i:s")])
                            ->limit(mt_rand(3, 10))
                            ->get();

                    foreach ($medicalCases as $md) {
                        foreach ($patients as $pt) {
                            $insertData[] = [
                                'medical_case_id' => $md->id,
                                'patient_id' => $pt->id,
                            ];
                        }
                    }

                    DB::table('medical_case_patients')
                    ->insert($insertData);
                    unset($insertData);
                });
    }

}