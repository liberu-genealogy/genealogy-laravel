<?php

namespace Tests\Unit\Filament\Pages;

use App\Filament\Pages\PrivateMessagingPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivateMessagingPageTest extends TestCase
{
    // database interactions may be necessary for this page
    use RefreshDatabase;

    public function test_mount(): void
    {
        $page = new PrivateMessagingPage;

        $this->assertInstanceOf(PrivateMessagingPage::class, $page);

        // mount() on the stub returns void without throwing
        $result = $page->mount();
        $this->assertNull($result);
    }

    public function test_send_message(): void
    {
        $page = new PrivateMessagingPage;

        $this->assertInstanceOf(PrivateMessagingPage::class, $page);

        // sendMessage() on the stub returns void without throwing
        $result = $page->sendMessage();
        $this->assertNull($result);
    }
}
