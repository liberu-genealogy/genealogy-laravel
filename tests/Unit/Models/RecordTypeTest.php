<?php

namespace Tests\Unit\Models;

use App\Models\RecordType;
use App\Models\Source;
use App\Models\SmartMatch;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_record_type_can_be_created(): void
    {
        $recordType = RecordType::create([
            'name' => 'Test Census',
            'slug' => 'test-census',
            'category' => 'census',
            'description' => 'Test census record type',
            'icon' => 'heroicon-o-users',
            'color' => 'primary',
            'is_active' => true,
            'sort_order' => 100,
        ]);

        $this->assertDatabaseHas('record_types', [
            'name' => 'Test Census',
            'slug' => 'test-census',
            'category' => 'census',
        ]);

        $this->assertTrue($recordType->is_active);
        $this->assertEquals(100, $recordType->sort_order);
    }

    public function test_record_type_category_check_methods(): void
    {
        $newspaperType = RecordType::create([
            'name' => 'Newspaper',
            'slug' => 'newspaper',
            'category' => 'newspaper',
        ]);

        $this->assertTrue($newspaperType->isNewspaper());
        $this->assertFalse($newspaperType->isCensus());
        $this->assertFalse($newspaperType->isParish());

        $censusType = RecordType::create([
            'name' => 'Census',
            'slug' => 'census',
            'category' => 'census',
        ]);

        $this->assertTrue($censusType->isCensus());
        $this->assertFalse($censusType->isNewspaper());
    }

    public function test_get_findmypast_types_returns_correct_array(): void
    {
        $types = RecordType::getFindMyPastTypes();

        $this->assertIsArray($types);
        $this->assertContains('newspaper', $types);
        $this->assertContains('parish', $types);
        $this->assertContains('census', $types);
        $this->assertContains('electoral', $types);
    }
}
