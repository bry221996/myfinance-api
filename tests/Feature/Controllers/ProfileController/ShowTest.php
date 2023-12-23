<?php

namespace Tests\Feature\Controllers\ProfileController;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group profiles
     * @group profiles.show
     */
    public function user_can_get_owned_profile(): void
    {
        Sanctum::actingAs($user = User::factory()->create());

        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->getJson("api/profiles/$profile->id")
            ->assertSuccessful()
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->where('data.id', $profile->id)
                    ->where('data.name', $profile->name)
                    ->where('data.description', $profile->description)
                    ->etc()
            );

    }

    /**
     * @test
     * @group profiles
     * @group profiles.show
     */
    public function user_cannot_get_other_user_profile(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $profile = Profile::factory()->create();

        $this->getJson("api/profiles/$profile->id")
            ->assertNotFound();
    }
}
