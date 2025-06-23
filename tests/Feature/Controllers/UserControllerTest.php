<?php

namespace Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * User who sent friend request is 'user_id' in 'user_friend' table
 * User who received friend request is 'friend_id' in 'user_friend' table
 */
class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;
    private User $friend;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['pseudo' => 'john']);
        $this->friend = User::factory()->create(['pseudo' => 'jane']);
    }

    public function test_user_can_search_user_by_pseudo(): void
    {
        $pseudo = $this->friend->pseudo;

        $response = $this->actingAs($this->user)
            ->get("/api/users/pseudo/$pseudo");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->friend->getKey(),
            'pseudo' => $this->friend->pseudo,
            'email' => $this->friend->email,
        ]);
    }

    public function test_user_can_send_friend_request(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/api/users/friends/send", ['user_id' => $this->friend->getKey()]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Friend request sent']);
        $this->assertDatabaseHas('user_friend', [
            'from_user_id' => $this->user->getKey(),
            'to_user_id' => $this->friend->getKey(),
            'status' => 'pending'
        ]);
    }

    public function test_user_can_accept_friend_request(): void
    {
        // $this->user receive friend request from $this->friend
        $this->user->friendsIncoming()->syncWithoutDetaching([
            $this->friend->getKey() => ['status' => 'pending']
        ]);

        $response = $this->actingAs($this->user)
            ->patch("/api/users/friends/action", [
                'user_id' => $this->friend->getKey(),
                'status' => 'accepted'
            ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => "Friend request accepted."]);
        $this->assertDatabaseHas('user_friend', [
            'from_user_id' => $this->friend->getKey(),
            'to_user_id' => $this->user->getKey(),
            'status' => 'accepted'
        ]);
    }

    public function test_user_can_reject_friend_request(): void
    {
        // $this->user receive friend request from $this->friend
        $this->user->friendsIncoming()->syncWithoutDetaching([
            $this->friend->getKey() => ['status' => 'pending']
        ]);

        $response = $this->actingAs($this->user)
            ->patch("/api/users/friends/action", [
                'user_id' => $this->friend->getKey(),
                'status' => 'rejected'
            ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => "Friend request rejected."]);
        $this->assertDatabaseHas('user_friend', [
            'from_user_id' => $this->friend->getKey(),
            'to_user_id' => $this->user->getKey(),
            'status' => 'rejected'
        ]);
    }

    public function test_user_can_block_friend_request(): void
    {
        // $this->user receive friend request from $this->friend
        $this->user->friendsIncoming()->syncWithoutDetaching([
            $this->friend->getKey() => ['status' => 'pending']
        ]);

        $response = $this->actingAs($this->user)
            ->patch("/api/users/friends/action", [
                'user_id' => $this->friend->getKey(),
                'status' => 'blocked'
            ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => "Friend request blocked."]);
        $this->assertDatabaseHas('user_friend', [
            'from_user_id' => $this->friend->getKey(),
            'to_user_id' => $this->user->getKey(),
            'status' => 'blocked'
        ]);
    }

    public function test_user_can_remove_friend(): void
    {
        // $this->user receive friend request from $this->friend
        $this->user->friendsIncoming()->syncWithoutDetaching([
            $this->friend->getKey() => ['status' => 'accepted']
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/api/users/friends/remove", [
                'user_id' => $this->friend->getKey(),
            ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Friend removed']);
        $this->assertDatabaseMissing('user_friend', [
            'from_user_id' => $this->friend->getKey(),
            'to_user_id' => $this->user->getKey(),
        ]);
    }

}
