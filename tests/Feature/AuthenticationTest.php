<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_a_hashed_account_and_requires_login(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Nadia Putri',
            'email' => 'nadia@example.test',
            'password' => 'student-password',
            'password_confirmation' => 'student-password',
        ]);

        $student = User::query()->where('email', 'nadia@example.test')->firstOrFail();

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Akun berhasil dibuat. Silakan masuk.');
        $this->assertGuest();
        $this->assertTrue(Hash::check('student-password', $student->password));
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Akun berhasil dibuat. Silakan masuk.');

        $loginResponse = $this->post(route('login.store'), [
            'email' => 'nadia@example.test',
            'password' => 'student-password',
        ]);

        $loginResponse->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($student);
    }

    public function test_invalid_credentials_do_not_authenticate_the_student(): void
    {
        User::factory()->create([
            'email' => 'nadia@example.test',
            'password' => 'correct-password',
        ]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'nadia@example.test',
            'password' => 'incorrect-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email' => 'Email atau kata sandi tidak sesuai.']);
        $this->assertGuest();
    }

    public function test_guest_requests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_students_can_open_the_dashboard(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSeeLivewire('dashboard.overview');
    }
}
