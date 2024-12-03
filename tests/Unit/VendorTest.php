<?php

namespace Tests\Unit;

use App\Models\Vendor;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use WithFaker;

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/vendors')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'name',
                        'address',
                        'city',
                        'state',
                        'zip',
                        'contact_name',
                        'contact_email',
                        'contact_phone',
                        'product',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testVendorIsCreatedSuccessfully()
    {
        $payload = [
            'name' => $this->faker->unique()->company,
            'product' => $this->faker->randomElement(['EPB', 'VS', 'EPB & VS', 'Service', 'Other']),
        ];

        $this->json('post', 'api/vendors', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'product',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('vendors', $payload);
    }

    public function testVendorIsUpdatedSuccessfully()
    {
       $vendor = Vendor::create(
            [
                'name' => $this->faker->unique()->company,
                'product' => $this->faker->randomElement(['EPB', 'VS', 'EPB & VS', 'Service', 'Other']),
            ]
        );

        $payload = [
            'name' => $this->faker->unique()->company,
            'product' => $this->faker->randomElement(['EPB', 'VS', 'EPB & VS', 'Service', 'Other']),
        ];

        $this->json('put', "api/vendors/$vendor->id", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'product',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('vendors', $payload);
    }

    public function testVendorIsDestroyed()
    {
       $vendor = Vendor::factory()->create();

        $this->json('delete', "api/vendors/$vendor->id")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('vendors', ['id' => $vendor->id]);
    }
}
