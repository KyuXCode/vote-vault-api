<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Disposition;

class DispositionControllerTest extends TestCase
{
    /** @test */
    public function it_can_list_dispositions()
    {
        $Disposition = Disposition::factory()->count(3)->create();

        $response = $this->getJson('/api/dispositions');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

}
