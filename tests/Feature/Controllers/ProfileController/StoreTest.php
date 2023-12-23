<?php

namespace Tests\Feature\Controllers\ProfileController;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group profiles
     * @group profiles.store
     */
    public function user_can_create_profile(): void
    {
        Sanctum::actingAs($user = User::factory()->create());

        $profile = Profile::factory()->make(['user_id' => $user->id]);

        $this->postJson('api/profiles', $data = $profile->toArray())
            ->assertSuccessful()
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('data.name', $profile->name)
                    ->where('data.description', $profile->description)
                    ->etc()
            );

        $this->assertDatabaseHas('profiles', $data);
    }

    /**
     * @test
     * @group profiles
     * @group profiles.store
     * @dataProvider validationTestData
     */
    public function user_cannot_create_profile_if_there_is_validation_error(array $overrides, string $field): void
    {
        Sanctum::actingAs($user = User::factory()->create());

        $profile =  Profile::factory()->make(['user_id' => $user->id, ...$overrides]);

        $this->postJson('api/profiles', $data = $profile->toArray())
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor($field);
    }

    public static function validationTestData(): array
    {
        return [
            [['name' => null],  "name"],
            [['description' => null],  "description"],
        ];
    }
}
