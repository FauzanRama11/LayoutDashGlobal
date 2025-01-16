<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class RoleAccessTest extends TestCase
{

    // DECISION TABLE 
    public function test_redirect_to_login_page_if_not_logged_in()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Welcome back! Log in to your Global Unair account.');
    }

    public function test_redirect_to_home_page_if_already_logged_in()
    {
        $credentials = ['usernameLog' => 'fa_ftmm', 'passwordLog' => 'unair123'];
        $this->post('/', $credentials);

        $response = $this->get('/');
        $response->assertRedirect('/fakultas/dashboard');
    }

    public function test_access_home_page_with_valid_role()
    {
        $credentials = ['usernameLog' => 'fa_ftmm', 'passwordLog' => 'unair123'];
        $this->post('/', $credentials);

        $response = $this->get('/fakultas/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Ini Halaman Berita Fakultas Teknologi Maju dan Multidisiplin');
    }

    public function test_redirect_to_login_when_accessing_home_without_login()
    {
        $response = $this->get('/fakultas/dashboard');

        $response->assertRedirect('/');
    }

    public function test_access_mitra_akademik_with_valid_role()
    {
        $credentials = ['usernameLog' => 'fa_ftmm', 'passwordLog' => 'unair123'];
        $this->post('/', $credentials);

        $response = $this->get('/mitra-akademik/index');
        $response->assertStatus(200);
        $response->assertSee('Mitra Akademik');
    }

    public function test_redirect_to_403_when_accessing_mitra_akademik_with_invalid_role()
    {
        $credentials = ['usernameLog' => 'gmp', 'passwordLog' => 'unair123'];
        $this->post('/', $credentials);

        $response = $this->get('/mitra-akademik/index');
        $response->assertStatus(403);
        $response->assertSee('403 - Unauthorized Actions');
    }

    // STATE TRANSITION

    public function test_redirect_to_login_when_not_logged_in()
    {
        $response = $this->get('/');

            $response->assertStatus(200);
            $response->assertSee('Welcome back! Log in to your Global Unair account.');
    }
  
    public function test_redirect_to_home_page_if_logged_in()
    {
        // Kirimkan kredensial untuk login
        $credentials = ['usernameLog' => 'fa_ftmm', 'passwordLog' => 'unair123'];
        $response = $this->post('/', $credentials);

        // Pastikan login berhasil
        $response->assertRedirect('/fakultas/dashboard');

        // Coba akses halaman root setelah login
        $response = $this->get('/');

        // Pastikan diarahkan ke dashboard fakultas
        $response->assertRedirect('/fakultas/dashboard');
    }

    public function test_access_home_page_with_valid_roles()
    {
        // Login dengan POST
        $credentials = ['usernameLog' => 'fa_ftmm', 'passwordLog' => 'unair123'];
        $response = $this->post('/', $credentials);

        // Pastikan login berhasil
        $response->assertRedirect('/fakultas/dashboard');

        // Akses dashboard fakultas
        $response = $this->get('/fakultas/dashboard');

        // Pastikan status 200
        $response->assertStatus(200);
        $response->assertSee('Ini Halaman Berita Fakultas Teknologi Maju dan Multidisiplin'); 
    }

    public function test_access_mitra_akademik_with_invalid_role()
    {
        // Login sebagai user dengan role 'GMP'
        $credentials = ['usernameLog' => 'gmp', 'passwordLog' => 'unair123'];
        $response = $this->post('/', $credentials);

        // Pastikan login berhasil
        $response->assertRedirect('/gmp/dashboard'); // Sesuaikan jika dashboard berbeda

        // Akses halaman mitra-akademik
        $response = $this->get('/mitra-akademik/index');

        // Pastikan mendapatkan status 403
        $response->assertStatus(403);
        $response->assertSee('403 - Unauthorized Actions'); // Sesuaikan dengan konten halaman Anda
    }

    public function test_access_invalid_routing()
    {
        // Login sebagai user dengan role 'GMP'
        $credentials = ['usernameLog' => 'fa_ftmm', 'passwordLog' => 'unair123'];
        $response = $this->post('/', $credentials);

        // Pastikan login berhasil
        $response->assertRedirect('/fakultas/dashboard'); // Sesuaikan jika dashboard berbeda

        // Akses route yang tidak valid
        $response = $this->get('/mitra-akademik/indeks');

        // Pastikan mendapatkan status 404
        $response->assertStatus(404);
        $response->assertSee('Sorry, something goes wrong'); 
    }
    
    // USE CASE

    public function test_login_with_valid_credentials()
    {
        // Data login valid
        $credentials = ['usernameLog' => 'fa_ftmm', 'passwordLog' => 'unair123'];

        // Simulasi login
        $response = $this->post('/', $credentials);
        $response->assertRedirect('/fakultas/dashboard');
    }

    public function test_login_with_invalid_credentials()
    {
        // Data login tidak valid
        $credentials = ['usernameLog' => 'fa_ftmm', 'passwordLog' => 'fakultas'];

        // Simulasi login
        $response = $this->post('/', $credentials);
        $response->assertRedirect('/');
    }
}
