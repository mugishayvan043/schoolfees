<?php

namespace Tests\Feature;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PaymentValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_prevents_overpayment_and_duplicate_submission(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345'),
            'role' => 'admin',
        ]);

        $class = SchoolClass::create(['class_name' => 'Form 1', 'description' => 'Test class']);
        Fee::create(['class_id' => $class->id, 'amount' => 100, 'academic_year' => config('fees.current_academic_year')]);
        $student = Student::create([
            'student_code' => 'STU-001',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'gender' => 'Male',
            'class_id' => $class->id,
        ]);

        $this->actingAs($admin)->get(route('payments.create'))->assertOk();
        $token = session('payment_form_token');

        $this->post(route('payments.store'), [
            'student_id' => $student->id,
            'amount_paid' => 150,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Cash',
            'payment_token' => $token,
        ])->assertSessionHasErrors('amount_paid');

        $this->post(route('payments.store'), [
            'student_id' => $student->id,
            'amount_paid' => 50,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Cash',
            'payment_token' => $token,
        ])->assertRedirect();

        $this->assertSame(1, Payment::count());

        $this->post(route('payments.store'), [
            'student_id' => $student->id,
            'amount_paid' => 25,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Cash',
            'payment_token' => $token,
        ])->assertSessionHasErrors('amount_paid');
    }
}
