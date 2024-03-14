<?php

namespace Tests\Unit\Filament\Pages;

use PHPUnit\Framework\TestCase;
use App\Filament\Pages\PrivateMessagingPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Message;

class PrivateMessagingPageTest extends TestCase
{
    public function test_mount()
    {
        // Create an instance of PrivateMessagingPage
        $page = new PrivateMessagingPage();

    // Set up the necessary dependencies and data
    // ...
    
    // Add your test logic here
    // Assert that the data is as expected

        // Call the mount method
        $page->mount();

        // Assert that the data is as expected
        // ...
    }

    public function test_sendMessage()
    {
    public function test_sendMessage()
    {
        // Create an instance of PrivateMessagingPage
        $page = new PrivateMessagingPage();

    // Set up the necessary dependencies and data
    // ...
    
    // Add your test logic here

        // Call the sendMessage method
        $response = $page->sendMessage();

        // Assert that the message is saved correctly
        // ...

        // Assert that the redirect response is as expected
        // ...
    }

    // Add additional test methods to cover edge cases and different scenarios
    // ...
}
    public function test_sendMessage()
    {
        // Create an instance of PrivateMessagingPage
        $page = new PrivateMessagingPage();

        // Set up the necessary dependencies and data
        // ...
        
        // Add your test logic here

        // Call the sendMessage method
        $response = $page->sendMessage();

        // Assert that the message is saved correctly
        // ...

        // Assert that the redirect response is as expected
        // ...
    }

    // Add additional test methods to cover edge cases and different scenarios
    // ...
    public function testSendMessage()
    {
        // Create an instance of PrivateMessagingPage
        $page = new PrivateMessagingPage();

        // Set up the necessary dependencies and data
        // ...

        // Add your test logic here

        // Call the sendMessage method
        $response = $page->sendMessage();

        // Assert that the message is saved correctly
        // ...

        // Assert that the redirect response is as expected
        // ...
    }

    // Add additional test methods to cover edge cases and different scenarios
    // ...
    public function testSendMessage()
    {
        // Create an instance of PrivateMessagingPage
        $page = new PrivateMessagingPage();

        // Set up the necessary dependencies and data
        // ...
        
        // Add your test logic here

        // Call the sendMessage method
        $response = $page->sendMessage();

        // Assert that the message is saved correctly
        // ...

        // Assert that the redirect response is as expected
        // ...
    }

    // Add additional test methods to cover edge cases and different scenarios
    // ...
