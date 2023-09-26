<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /** @test */
    public function visit_login_page_()
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('login');
    }
}
