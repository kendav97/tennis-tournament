<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Participant;
use Tests\TestCase;

class GameTest extends TestCase
{
    public function test_can_play_game_with_winner()
    {
        $this->postJson('api/v1/participants/clear');
        $this->postJson('api/v1/participants/seed', ['quantity' => 64]);

        $response = $this->postJson('api/v1/game/play', ['gender' => 'male']);

        $response->assertStatus(200)
                ->assertJsonStructure(['message', 'success']);

        $winner = Participant::where('is_defeated', Participant::IS_NOT_DEFEATED)->first();

        $message = $response->json()['message'];
        preg_match('/The winner is (.+) with id = (\d+)/', $message, $matches);
    
        $this->assertEquals($winner->id, (int)$matches[2]);
        $this->assertStringContainsString($winner->name, $message);
    }

    public function test_can_play_game_without_participants()
    {
        $this->postJson('api/v1/participants/clear');

        $response = $this->postJson('api/v1/game/play', ['gender' => 'male']);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'There are no participants available in the database.',
                    'success' => false
                ]);
    }

    public function test_can_reset_game()
    {
        $this->postJson('api/v1/participants/clear');
        $this->postJson('api/v1/participants/seed', ['quantity' => 64]);
        $this->postJson('api/v1/game/play', ['gender' => 'male']);

        $response = $this->postJson('api/v1/game/reset');

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Game reseted successfully',
                    'success' => true
                ]);

        $this->assertDatabaseMissing('participants', ['is_defeated' => Participant::IS_DEFEATED, 'deleted_at' => null]);
    }

    public function test_can_replay_game()
    {
        $this->postJson('api/v1/participants/clear');
        $this->postJson('api/v1/participants/seed', ['quantity' => 64]);
        $this->postJson('api/v1/game/play', ['gender' => 'male']);

        $response = $this->postJson('api/v1/game/replay', ['gender' => 'female']);

        $response->assertStatus(200)
                ->assertJsonStructure(['message', 'success']);

        $winner = Participant::where('is_defeated', Participant::IS_NOT_DEFEATED)->first();

        $message = $response->json()['message'];
        preg_match('/The winner is (.+) with id = (\d+)/', $message, $matches);
    
        $this->assertEquals($winner->id, (int)$matches[2]);
        $this->assertStringContainsString($winner->name, $message);
    }
}
