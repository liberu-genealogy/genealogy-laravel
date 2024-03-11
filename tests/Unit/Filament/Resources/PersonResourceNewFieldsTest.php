<?php

namespace Tests\Unit\Filament\Resources;

use App\Filament\Resources\PersonResource;
use Illuminate\Foundation\Testing\TestCase;

class PersonResourceNewFieldsTest extends TestCase
{
    public function test_father_id_field_exists()
    {
        $resource = new PersonResource();
        $form = $resource->form(new \Filament\Forms\Form());

        $this->assertArrayHasKey('father_id', $form->getSchema());
    }

    public function test_mother_id_field_exists()
    {
        $resource = new PersonResource();
        $form = $resource->form(new \Filament\Forms\Form());

        $this->assertArrayHasKey('mother_id', $form->getSchema());
    }

    public function test_father_id_field_label()
    {
        $resource = new PersonResource();
        $form = $resource->form(new \Filament\Forms\Form());
        $field = $form->getSchema()['father_id'];

        $this->assertEquals('Father', $field->getLabel());
    }

    public function test_mother_id_field_label()
    {
        $resource = new PersonResource();
        $form = $resource->form(new \Filament\Forms\Form());
        $field = $form->getSchema()['mother_id'];

        $this->assertEquals('Mother', $field->getLabel());
    }

    public function test_father_id_field_required()
    {
        $resource = new PersonResource();
        $form = $resource->form(new \Filament\Forms\Form());
        $field = $form->getSchema()['father_id'];

        $this->assertTrue($field->isRequired());
    }

    public function test_mother_id_field_required()
    {
        $resource = new PersonResource();
        $form = $resource->form(new \Filament\Forms\Form());
        $field = $form->getSchema()['mother_id'];

        $this->assertTrue($field->isRequired());
    }

    public function test_father_id_field_relationship()
    {
        $resource = new PersonResource();
    public function test_tenant_middleware_functionality()
    {
        // Create a mock HTTP request instance
        $request = $this->getMockBuilder(\Illuminate\Http\Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        // TODO: Add assertions for the new tenant middleware functionality
    }
        $form = $resource->form(new \Filament\Forms\Form());
        $field = $form->getSchema()['father_id'];

        $this->assertEquals('father', $field->getRelationship());
        $this->assertEquals('name', $field->getRelationshipLabel());
    }

    public function test_mother_id_field_relationship()
    {
        $resource = new PersonResource();
        $form = $resource->form(new \Filament\Forms\Form());
        $field = $form->getSchema()['mother_id'];

        $this->assertEquals('mother', $field->getRelationship());
        $this->assertEquals('name', $field->getRelationshipLabel());
    }
}
