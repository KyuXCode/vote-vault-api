<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;//Many forums suggest use of RefreshDatabase in the domain of unit tests to clear before testing.
use Illuminate\Http\Response;

/*This test class tests the CertificationController.php Class,
located in the api. These are the resources used to create the
test logic:
https://www.geeksforgeeks.org/how-to-test-php-code-with-phpunit/
https://laravel.com/docs/8.x/testing
https://www.youtube.com/watch?v=UjA-16diixc&ab_channel=CodeWithDary
*/

class CertificationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_certification()
    {
        //This line creates a vendor record to attach to an id, to then attribute it to a certification record.
        $vendor = Vendor::factory()->create();

        //This is random data to be inserted into the certification record.
        $certificationData = [
            'model_number' => 'Model 123',
            'description' => 'Test certification description',
            'application_date' => '2023-01-01',
            'certification_date' => '2023-02-01',
            'expiration_date' => '2024-01-01',
            'federal_certification_number' => 'FCN123',
            'federal_certification_date' => '2023-01-15',
            'type' => 'Certification',
            'action' => 'Approved',
            'system_type' => 'VS',
            'system_base' => 'DRE',
            'vendor_id' => $vendor->id,
        ];

        //Calls the api to make a certification record and inserts the data from the certificationData variable.
        $response = $this->postJson('/api/certifications', $certificationData);

        //This tests that the api was reached and created the record giving status response 201 if done successfully.
        $response->assertStatus(Response::HTTP_CREATED)
                 ->assertJsonFragment([
                     'model_number' => 'Model 123',
                     'description' => 'Test certification description',
                     'application_date' => '2023-01-01',
                     'certification_date' => '2023-02-01',
                     'expiration_date' => '2024-01-01',
                     'federal_certification_number' => 'FCN123',
                     'federal_certification_date' => '2023-01-15',
                     'type' => 'Certification',
                     'action' => 'Approved',
                     'system_type' => 'VS',
                     'system_base' => 'DRE',
                     'vendor_id' => $vendor->id,
                 ]);

        //This reaches the database directly to test if the information reached it.
        $this->assertDatabaseHas('certifications', [
            'model_number' => 'Model 123',
            'description' => 'Test certification description',
            'application_date' => '2023-01-01',
            'certification_date' => '2023-02-01',
            'expiration_date' => '2024-01-01',
            'federal_certification_number' => 'FCN123',
            'federal_certification_date' => '2023-01-15',
            'type' => 'Certification',
            'action' => 'Approved',
            'system_type' => 'VS',
            'system_base' => 'DRE',
            'vendor_id' => $vendor->id,
        ]);
    }
}
