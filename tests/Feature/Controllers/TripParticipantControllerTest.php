<?php

namespace Tests\Feature\Controllers;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Utils\UserTestHelper;

class TripParticipantControllerTest extends TestCase
{
    use DatabaseTransactions, UserTestHelper;

    private Trip $trip;
    private User $author;
    private Collection $users;

    protected function setUp(): void
    {
        parent::setUp();
        $this->users = $this->createUsersWithDefaultPermissions(2);
        $this->author = $this->createUsersWithDefaultPermissions(1);
        $this->trip = Trip::factory()->create([
            'author_id' => $this->author->getKey(),
        ]);
    }

    public function test_author_can_add_participants_to_trip(): void
    {
        $tripId = $this->trip->getKey();

        $response = $this->actingAs($this->author)
            ->post("/api/trips/$tripId/participants", [
                'participants' => [
                    [
                        'user_id' => $this->users[0]->getKey(),
                        'permission' => 'view'
                    ],
                    [
                        'user_id' => $this->users[1]->getKey(),
                        'permission' => 'update'
                    ]
                ]
            ]);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Participants added successfully']);
        $this->assertDatabaseHas('user_trip',
            [
                'user_id' => $this->users[0]->getKey(),
                'trip_id' => $tripId,
                'permission' => 'view'
            ]
        );
        $this->assertDatabaseHas('user_trip',
            [
                'user_id' => $this->users[1]->getKey(),
                'trip_id' => $tripId,
                'permission' => 'update'
            ]
        );
    }

    public function test_author_update_participant_permission(): void
    {
        $tripId = $this->trip->getKey();
        $user = $this->users[0];
        $this->trip->participants()->attach([
            $user->getKey() => ['permission' => 'view'],
        ]);

        $this->assertDatabaseHas('user_trip', [
            'user_id' => $user->getKey(),
            'trip_id' => $tripId,
            'permission' => 'view',
        ]);

        $response = $this->actingAs($this->author)
            ->patch("/api/trips/$tripId/participants", [
                'participants' => [
                    [
                        'user_id' => $this->users[0]->getKey(),
                        'permission' => 'update'
                    ]
                ]
            ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Participant\'s permissions updated']);

        $this->assertDatabaseHas('user_trip', [
            'user_id' => $user->getKey(),
            'trip_id' => $tripId,
            'permission' => 'update',
        ]);
    }

    public function test_can_author_can_remove_participant_from_trip(): void
    {
        $tripId = $this->trip->getKey();
        $user = $this->users[0];
        $this->trip->participants()->attach([
            $user->getKey() => ['permission' => 'view'],
        ]);

        $this->assertDatabaseHas('user_trip', [
            'user_id' => $user->getKey(),
            'trip_id' => $tripId,
            'permission' => 'view',
        ]);

        $response = $this->actingAs($this->author)
            ->delete("/api/trips/$tripId/participants", [
                'participants' => [
                    [
                        'user_id' => $this->users[0]->getKey(),
                    ]
                ]
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('user_trip', [
            'user_id' => $user->getKey(),
            'trip_id' => $tripId,
            'permission' => 'view',
        ]);
    }

}
