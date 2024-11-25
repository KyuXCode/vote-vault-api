<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\Component;
use App\Models\Certification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

/*This test class tests the ComponentController.php Class,
located in the api. These are the resources used to create the
test logic:
https://www.geeksforgeeks.org/how-to-test-php-code-with-phpunit/
https://laravel.com/docs/8.x/testing
https://www.youtube.com/watch?v=UjA-16diixc&ab_channel=CodeWithDary
*/
class ComponentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_components_test()
    {
        //makes 3 components
        Component::factory()->count(3)->create();

        //Requests the components and sets the api response to the response variable.
        $response = $this->getJson('/api/components');

        //Checks if api's response is 200 and if there are 3 components.
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function create_component()
    {
        //Creates certification record to attach to component record. Needed due to foreign key constraint.
        $certification = Certification::factory()->create();

        //Random data to be inserted into the component record.
        $componentData = [
            'name' => 'Component 1',
            'description' => 'Test component description',
            'type' => 'DRE',
            'certification_id' => $certification->id,
        ];

        //Calls the api to make a component record using post, and inserts the data from the componentData variable.
        $response = $this->postJson('/api/components', $componentData);

        //Checks that the response is successful and the component is created with accurate data.
        $response->assertStatus(Response::HTTP_CREATED)
                 ->assertJsonFragment([
                     'name' => 'Component 1',
                     'description' => 'Test component description',
                     'type' => 'DRE',
                     'certification_id' => $certification->id,
                 ]);

        //This Checks that the component exists in the database
        $this->assertDatabaseHas('components', [
            'name' => 'Component 1',
            'description' => 'Test component description',
            'type' => 'DRE',
            'certification_id' => $certification->id,
        ]);
    }

    /** @test */
    public function show_component_test()
    {
        //Creates an empty component record
        $component = Component::factory()->create();

        //Combines api url with component id to get the component
        $response = $this->getJson('/api/components/' . $component->id);

        //Checks that the response is successful and contains the component
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $component->id,
                     'name' => $component->name,
                     'description' => $component->description,
                     'type' => $component->type,
                     'certification_id' => $component->certification_id,
                 ]);
    }
}
