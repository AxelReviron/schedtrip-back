<?php

namespace Tests\Feature\Policies;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Utils\UserTestHelper;

class TripParticipantPolicyTest extends TestCase
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

    public function test_author_can_manage_participants(): void
    {
        $this->assertTrue($this->author->can('manageParticipants', $this->trip));
    }

    public function test_participant_can_see_trip(): void
    {
        $user = $this->users[0];
        $this->trip->participants()->attach([
            $user->getKey() => ['permission' => 'view'],
        ]);

        $this->assertTrue($this->users[0]->can('view', $this->trip));
    }

    public function test_participant_can_update_trip(): void
    {
        $user = $this->users[0];
        $this->trip->participants()->attach([
            $user->getKey() => ['permission' => 'update'],
        ]);

        $this->assertTrue($this->users[0]->can('update', $this->trip));
    }

}
