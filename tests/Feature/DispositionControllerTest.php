<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Disposition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DispositionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_dispositions()
    {
        $Disposition = Disposition::factory()->count(3)->create();

        $response = $this->getJson('/api/dispositions');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

}
