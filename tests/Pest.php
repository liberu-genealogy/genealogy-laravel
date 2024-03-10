<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

uses(Tests\TestCase::class, RefreshDatabase::class, WithoutMiddleware::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Custom Helpers
|--------------------------------------------------------------------------
|
| Here you may define custom helper functions for your tests, allowing you
| to avoid repetitive code and focus on what's important: testing your application.
| These helpers can perform common tasks like logging in a user, creating models, etc.
|
*/

function loginUser()
{
    $user = \App\Models\User::factory()->create();
    \Illuminate\Support\Facades\Auth::login($user);

    return $user;
}

/*
|--------------------------------------------------------------------------
| Custom Expectations
|--------------------------------------------------------------------------
|
| Pest allows you to extend its expectation API with custom methods.
| These methods can be used to expressively test your application, making
| your tests more readable and focused on your application's requirements.
|
*/

expect()->extend('toBeAccessible', function () {
    $accessibilityReport = \Pest\Accessibility\checkAccessibility($this->value);
    \PHPUnit\Framework\Assert::assertTrue($accessibilityReport->isAccessible(), 'Expected page to be accessible, but it was not.');

    return $this;
});

/*
|--------------------------------------------------------------------------
| Test Case Templates
|--------------------------------------------------------------------------
|
| You can define common test case structures using higher-order tests.
| These are useful for defining a standard way to test certain features
| of your application, such as CRUD operations on Filament resources.
|
*/

test('resources can be created', function () {
    $user = loginUser();
    $data = \App\Models\Publication::factory()->make()->toArray();

    $this->post(route('publications.store'), $data)
         ->assertStatus(302)
         ->assertSessionHas('success', 'Publication created successfully.');

    $this->assertDatabaseHas('publications', $data);
})->with('publicationResources');

test('resources can be updated', function () {
    $user = loginUser();
    $publication = \App\Models\Publication::factory()->create();
    $updatedData = ['name' => 'Updated Name'];

    $this->put(route('publications.update', $publication), $updatedData)
         ->assertStatus(302)
         ->assertSessionHas('success', 'Publication updated successfully.');

    $this->assertDatabaseHas('publications', $updatedData);
})->with('publicationResources');

test('resources can be deleted', function () {
    $user = loginUser();
    $publication = \App\Models\Publication::factory()->create();

    $this->delete(route('publications.destroy', $publication))
         ->assertStatus(302)
         ->assertSessionHas('success', 'Publication deleted successfully.');

    $this->assertDatabaseMissing('publications', ['id' => $publication->id]);
})->with('publicationResources');
