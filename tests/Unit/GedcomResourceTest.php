<?php

namespace Tests\Unit;

use App\Filament\Resources\GedcomResource;
use App\Models\Gedcom;
use Illuminate\Foundation\Testing\TestCase;

class GedcomResourceTest extends TestCase
{
    public function testEventHandlersRegistered()
    {
        // TODO: Write test code to assert that the event handler is properly registered
    }

    public function testDispatchesImportGedcomJob()
    {
        // TODO: Write test code to assert that the event handler dispatches the ImportGedcom job with the correct parameters
    }

    public function testDoesNotDispatchImportGedcomJobIfStateIsNull()
    {
        // TODO: Write test code to assert that the event handler does not dispatch the ImportGedcom job if the state is null
    }

    public function testStoresFileInCorrectDirectory()
    {
        // TODO: Write test code to assert that the event handler stores the file in the correct directory
    }

    public function testSetsFileVisibilityToPrivate()
    {
        // TODO: Write test code to assert that the event handler sets the file visibility to private
    }

    public function testSetsMaximumFileSize()
    {
        // TODO: Write test code to assert that the event handler sets the maximum file size correctly
    }
}
