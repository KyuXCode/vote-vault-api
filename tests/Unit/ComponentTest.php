<?php

namespace Tests\Unit;

use App\Models\Component;
use App\Models\Certification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ComponentTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/components')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'type',
                        'certification_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testValidationErrorsArePresent()
    {
        $Certification = Certification::factory()->withAllFields()->make();

        $this->json('post', 'api/components', $Certification->toArray())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'type', 'certification_id']);

        self::assertTrue(Component::where('Component Name Example', $Certification->name)->doesntExist());
    }

    public function testComponentIsCreatedSuccessfully()
    {
        $Component = Component::factory()->withAllFields()->make();

        $this->json('post', 'api/components', $Component->toArray())
            ->assertCreated()
            ->assertJson($Component->toArray());

        self::assertTrue(
            Component::where('Component Name Example', $Component->name)->exists()
        );
    }

    public function testComponentIsUpdatedSuccessfully()
    {
        $Component = Component::factory()->withAllFields()->create();
        $Component->name = 'Update Name';

        $this->json('put', "api/components/{$Component->id}", $Component->toArray())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($Component->toArray());

        self::assertTrue(
            $Component::where('Update Name', $Component->name)->exists()
        );
    }

    public function testComponentIsDestroyed()
    {
        $component = Component::factory()->create();

        $this->json('delete', "api/components/{$component->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('components', ['id' => $component->id]);
    }
}
