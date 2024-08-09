<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TournamentTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    public function test_can_get_list(): void
    {
        $this->postJson('api/v1/participants/clear');
        $this->postJson('api/v1/participants/seed', ['quantity' => 64]);
        $this->postJson('api/v1/game/play', ['gender' => 'male']);
        $this->postJson('api/v1/game/replay', ['gender' => 'female']);

        $response = $this->getJson('api/v1/tournaments');

        $response->assertStatus(200);

        $response->assertJsonCount(2);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'gender',
                'participant' => [
                    'id',
                    'name',
                ],
                'created_at',
            ],
        ]);
    }
}
