<?php

namespace Tests\Feature\Controllers\ProfileController;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @group profiles
     * @group profiles.update
     */
    public function user_can_updated_owned_profile(): void
    {
        Sanctum::actingAs($user = User::factory()->create());

        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $updatedProfile = Profile::factory()->make(['user_id' => $user->id]);

        $this->putJson("api/profiles/$profile->id", $data = $updatedProfile->toArray())
            ->assertSuccessful()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('data.id', $profile->id)
                    ->where('data.name', $updatedProfile->name)
                    ->where('data.description', $updatedProfile->description)
                    ->etc()
            );

        $this->assertDatabaseHas('profiles', $data);
    }

    /**
     * @test
     *
     * @group profiles
     * @group profiles.update
     */
    public function user_cannot_update_other_user_profile(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $profile = Profile::factory()->create();

        $this->putJson("api/profiles/$profile->id")
            ->assertForbidden();
    }

    /**
     * @test
     *
     * @group profiles
     * @group profiles.update
     *
     * @dataProvider validationTestData
     */
    public function user_cannot_update_profile_if_there_is_validation_error(array $overrides, string $field): void
    {
        Sanctum::actingAs($user = User::factory()->create());

        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $updatedProfile = Profile::factory()->make(['user_id' => $user->id, ...$overrides]);

        $this->putJson("api/profiles/$profile->id", $updatedProfile->toArray())
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor($field);
    }

    public static function validationTestData(): array
    {
        return [
            [['name' => null],  'name'],
            [['description' => null],  'description'],
        ];
    }
}
