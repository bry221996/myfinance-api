<?php

namespace Tests\Feature\Controllers\ProfileController;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group profiles
     * @group profiles.destroy
     */
    public function user_can_delete_owned_profile(): void
    {
        Sanctum::actingAs($user = User::factory()->create());

        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->deleteJson("api/profiles/$profile->id")
            ->assertSuccessful();

        $this->assertSoftDeleted($profile);

    }

    /**
     * @test
     * @group profiles
     * @group profiles.destroy
     */
    public function user_cannot_delete_other_user_profile(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $profile = Profile::factory()->create();

        $this->deleteJson("api/profiles/$profile->id")
            ->assertNotFound();

        $this->assertNotSoftDeleted($profile);
    }
}
