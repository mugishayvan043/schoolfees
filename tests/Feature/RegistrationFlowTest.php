<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_hashed_user_and_redirects_to_login(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Mugisha Yvan',
            'email' => 'yvan@example.com',
            'password' => '12345',
            'password_confirmation' => '12345',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Account created successfully');

        $user = User::where('email', 'yvan@example.com')->firstOrFail();

        $this->assertSame('admin', $user->role);
        $this->assertTrue(Hash::check('12345', $user->password));
    }

    public function test_registration_rejects_duplicate_email_and_short_password(): void
    {
        User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => Hash::make('12345'),
            'role' => 'accountant',
        ]);

        $response = $this->from(route('register'))->post(route('register.store'), [
            'name' => '',
            'email' => 'existing@example.com',
            'password' => '1234',
            'password_confirmation' => '9999',
            'role' => '',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_accountant_cannot_access_admin_user_management(): void
    {
        $accountant = User::create([
            'name' => 'Accountant',
            'email' => 'accountant@example.com',
            'password' => Hash::make('12345'),
            'role' => 'accountant',
        ]);

        $this->actingAs($accountant)
            ->get(route('users.index'))
            ->assertForbidden();
    }
}
