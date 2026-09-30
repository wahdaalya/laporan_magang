<?php

namespace Tests\Unit\Support;

use App\Support\ContactLink;
use PHPUnit\Framework\TestCase;

class ContactLinkTest extends TestCase
{
    public function test_local_indonesian_number_uses_the_country_code(): void
    {
        $url = ContactLink::whatsappUrl('0899-8877-6655');

        $this->assertSame('https://wa.me/6289988776655', $url);
    }

    public function test_existing_indonesian_country_code_is_preserved(): void
    {
        $url = ContactLink::whatsappUrl('+62 (899) 8877-6655');

        $this->assertSame('https://wa.me/6289988776655', $url);
    }

    public function test_empty_phone_number_does_not_create_a_link(): void
    {
        $this->assertNull(ContactLink::whatsappUrl(null));
        $this->assertNull(ContactLink::whatsappUrl(' +()- '));
    }
}
