<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_security_headers_are_added_to_application_responses(): void
    {
        $response = $this->get('/');

        $response
            ->assertRedirect(route('login'))
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
