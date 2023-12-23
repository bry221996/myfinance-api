<?php

namespace Tests\Feature\Controllers\ProfileController;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     * @group profiles
     * @group profiles.index
     */
    public function user_list_owned_profiles(): void
    {
        $this->actingAs($user = User::factory()->create());

        $profiles = Profile::factory()
            ->count($count  = $this->faker()->numberBetween(1, 5))
            ->create(['user_id' => $user->id]);

        $this->getJson('api/profiles')
            ->assertSuccessful()
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('meta')
                    ->has('links')
                    ->has(
                        'data',
                        $count,
                        fn (AssertableJson $json) =>
                        $json->where('id', $profiles->first()->id)
                            ->where('name', $profiles->first()->name)
                            ->where('description', $profiles->first()->description)
                            ->etc()
                    )
            );
    }

    /**
     * @test
     * @group profiles
     * @group profiles.index
     */
    public function user_cannot_list_other_user_profiles(): void
    {
        $this->actingAs(User::factory()->create());

        $profile = Profile::factory()->create();

        $this->getJson('api/profiles')
            ->assertJsonMissing([
                'id' => $profile->id,
                'name' => $profile->name
            ]);
    }
}
