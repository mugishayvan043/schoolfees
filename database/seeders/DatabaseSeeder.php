<?php

namespace Database\Seeders;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $classes = collect(['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5'])->map(function ($name, $index) {
            return SchoolClass::updateOrCreate(
                ['class_name' => $name],
                ['description' => 'Academic class level '.($index + 1)]
            );
        });

        $classes->each(function (SchoolClass $class, int $index) {
            Fee::updateOrCreate(
                ['class_id' => $class->id, 'academic_year' => '2026'],
                ['amount' => 850 + ($index * 120)]
            );
        });

        $firstNames = ['Amina','Brian','Chipo','Daniel','Elsa','Farai','Grace','Henry','Irene','Jacob','Kudzai','Linda','Moses','Nadia','Oscar','Priscilla','Quinton','Ruth','Samuel','Tariro','Unity','Vusi','Wendy','Xolani','Yvonne'];
        $lastNames = ['Moyo','Dlamini','Ncube','Khumalo','Nkosi','Mabaso','Sibanda','Maseko','Phiri','Banda','Mthembu','Zulu','Mokoena','Mahlangu','Naidoo','Gumede','Baloyi','Masego','Shabalala','Mutasa'];

        for ($i = 1; $i <= 50; $i++) {
            $class = $classes[($i - 1) % $classes->count()];
            Student::updateOrCreate(
                ['student_code' => sprintf('STU-2026-%03d', $i)],
                [
                    'first_name' => $firstNames[($i - 1) % count($firstNames)],
                    'last_name' => $lastNames[($i - 1) % count($lastNames)],
                    'gender' => $i % 2 === 0 ? 'Female' : 'Male',
                    'class_id' => $class->id,
                    'phone' => '+27 7'.sprintf('%08d', 1000000 + $i),
                    'address' => ($i + 10).' Main Road, School District',
                ]
            );
        }

        $recorder = User::first();

        if (! $recorder) {
            return;
        }

        Payment::query()->delete();
        $students = Student::with('schoolClass.fees')->get();

        foreach ($students->take(36) as $index => $student) {
            $fee = (float) $student->totalFee();
            $amount = match ($index % 4) {
                0 => $fee,
                1 => round($fee * 0.75, 2),
                2 => round($fee * 0.5, 2),
                default => round($fee * 0.25, 2),
            };

            Payment::create([
                'student_id' => $student->id,
                'amount_paid' => $amount,
                'payment_date' => now()->subDays(60 - $index)->toDateString(),
                'receipt_number' => Payment::nextReceiptNumber(),
                'payment_method' => ['Cash', 'Bank Transfer', 'Mobile Money', 'Card'][$index % 4],
                'recorded_by' => $recorder->id,
            ]);
        }
    }
}
