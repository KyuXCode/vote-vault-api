<?php

namespace Tests\Unit;

use App\Models\Certification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CertificationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testIndexReturnsDataInValidFormat()
    {
        // Given

        // When

        // Then


        $this->json('get', 'api/certifications')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'model_number',
                        'description',
                        'application_date',
                        'certification_date',
                        'expiration_date',
                        'federal_certification_number',
                        'federal_certification_date',
                        'type',
                        'action',
                        'system_type',
                        'system_base',
                        'vendor_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testCertificationIsCreatedSuccessfully()
    {
        $Certification = Certification::factory()->withAllFields()->make();

        $this->json('post', 'api/certifications', $Certification->toArray())
            ->assertStatus(Response::HTTP_CREATED)
            ->assertCreated()
            ->assertJson($Certification->toArray());

        self::assertTrue(
            $Certification::where('ABC_Test', $Certification->model_number)->exists()
        );
    }

    public function testValidationErrorsArePresent()
    {
        $Certification = Certification::factory()->withAllFields()->make();
        $Certification->description = null;


        $this->json('post', 'api/certifications', $Certification->toArray())
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'description',
            ]);

        self::assertTrue(Certification::where('ABC_Test', $Certification->model_number)->doesntExist());
    }

    public function testCertificationIsUpdatedSuccessfully()
    {
        $Certification = Certification::factory()->withAllFields()->create();
        $Certification->model_number = 'ABC_Update';

        $this->json('put', "api/certifications/{$Certification->id}", $Certification->toArray())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($Certification->toArray());

        self::assertTrue(
            $Certification::where('ABC_Update', $Certification->model_number)->exists()
        );
    }

    public function testCertificationIsDestroyed()
    {
        $certification = Certification::factory()->create();

        $this->json('delete', "api/certifications/$certification->id")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('certifications', ['id' => $certification->id]);
    }
}
