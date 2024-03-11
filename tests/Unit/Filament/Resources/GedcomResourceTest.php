<?php

use Tests\TestCase;
use App\Filament\Resources\GedcomResource;
use Filament\Forms\Form;
use App\Filament\Resources\GedcomResource;

namespace Tests\Unit\Filament\Resources;

use App\Filament\Resources\GedcomResource;
use App\Models\Gedcom;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GedcomResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testFormMethod()
    {
        // Create a mock Form instance
        $form = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Call the form method of GedcomResource
        $result = GedcomResource::form($form);

        // Assert that the result is an instance of Form
        $this->assertInstanceOf(Form::class, $result);

        // TODO: Add more assertions for the form method
    }

    public function testTableMethod()
    {
        // Create a mock Table instance
        $table = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Call the table method of GedcomResource
        $result = GedcomResource::table($table);

        // Assert that the result is an instance of Table
        $this->assertInstanceOf(Table::class, $result);

        // TODO: Add more assertions for the table method
    }

    public function testGetPagesMethod()
    {
        // Call the getPages method of GedcomResource
        $result = GedcomResource::getPages();

        // Assert that the result is an array
        $this->assertIsArray($result);

        // TODO: Add more assertions for the getPages method
    }

    public function testTenantMiddlewareFunctionality()
    {
        // Create a mock HTTP request instance
        $gedcom = $this->getMockBuilder(Gedcom::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        // Create a mock Form instance
use App\Filament\Resources\GedcomResource;
        $form = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        // Set up the mock Form instance to return the mock Gedcom instance
        $form->method('getModel')->willReturn($gedcom);
    
        // Call the form method of GedcomResource
use Filament\Forms\Form;
        $result = GedcomResource::form($form);
    
        // Assert that the result is an instance of Form
        $this->assertInstanceOf(Form::class, $result);
    
        $this->assertEquals('pedigree-chart', route('pedigree-chart'));
    }

    public function testAfterStateUpdatedEventHandler()
    {
        // TODO: Implement test for the afterStateUpdated event handler
    }
}
        // TODO: Write test logic to cover the import functionality in GedcomResource.php
    }
