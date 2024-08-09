<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Participant;
use Tests\TestCase;

class ParticipantTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_index_participants()
    {
        Participant::factory()->count(5)->create();

        $response = $this->getJson('api/v1/participants');

        $response->assertStatus(200)
                ->assertJsonCount(5, 'data')
                ->assertJsonStructure(['data' => ['*' => [
                    'id',
                    'name',
                    'skill',
                    'strength',
                    'speed',
                    'reaction',
                    'is_defeated',
                ]]]);
    }

    public function test_can_create_participant()
    {
        $data = [
            'name' => $this->faker()->name(),
            'skill' => random_int(0, 100),
            'strength' => random_int(0, 100),
            'speed' => random_int(0, 100),
            'reaction' => random_int(0, 100),
        ];
    
        $response = $this->postJson('api/v1/participants', $data);
    
        $response->assertStatus(201);

        $this->assertDatabaseHas('participants', $data);
    }

    public function test_can_seed_participants()
    {
        $quantity = 8;

        $response = $this->postJson('api/v1/participants/seed', ['quantity' => $quantity]);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => "$quantity aleatory participants created successfully"
                ]);

        $this->assertDatabaseCount('participants', $quantity);
    }

    public function test_can_clear_participants()
    {
        Participant::factory()->count(5)->create();

        $response = $this->postJson('api/v1/participants/clear');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'All participants deleted'
                ]);

        $this->assertDatabaseCount('participants', 5);
        $this->assertDatabaseHas('participants', ['deleted_at' => now()]);
    }

}
