<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;//Many forums suggest use of RefreshDatabase in the domain of unit tests to clear before testing.

/*This test class tests the VendorController.php Class,
located in the api. These are the resources used to create the
test logic:
https://www.geeksforgeeks.org/how-to-test-php-code-with-phpunit/
https://laravel.com/docs/8.x/testing
https://www.youtube.com/watch?v=UjA-16diixc&ab_channel=CodeWithDary
*/

class VendorControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_vendors_test()
    {
        Vendor::factory()->count(3)->create();//In this line we create 3 records of vendors.

        //Requests the vendors and sets it equal to the response variable.
        $response = $this->getJson('/api/vendors');

        //Recieves the status response from the api and accounts for the 3 vendor records.
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function create_vendor_test()
    {
        //Variable to pass information into vendor record.
        $vendorData = [
            'name' => 'Test Vendor',
            'address' => '123 Test St',
            'city' => 'Test City',
            'state' => 'Test State',
        ];

        //Creates a vendor record and inserts the data from the vendorData variable.
        $response = $this->postJson('/api/vendors', $vendorData);

        //Checks for status response 201 meaning the record was created
        $response->assertStatus(201)
        //The data here checks to see if the record was given the proper data from it's creation.
                 ->assertJsonFragment([
                     'name' => 'Test Vendor',
                     'address' => '123 Test St',
                     'city' => 'Test City',
                     'state' => 'Test State',
                 ]);

        //This checks the database to see if the record was created with the proper data.
        $this->assertDatabaseHas('vendors', [
            'name' => 'Test Vendor',
            'address' => '123 Test St',
            'city' => 'Test City',
            'state' => 'Test State',
        ]);
    }/*It is important to note that while the assertJsonFragment method
     is used to check the data of the record of the api response,
     the assertDatabaseHas method is used to check the database for
      the record and data accuracy AFTER it's creation.*/
}

