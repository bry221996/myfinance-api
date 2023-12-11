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
    public function user_can_create_profiles(): void
    {
        Sanctum::actingAs($user = User::factory()->create());

        $profile =  Profile::factory()->make(['user_id' => $user->id]);

        $this->postJson('api/profiles', $data = $profile->toArray())
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('data.name', $profile->name)
                    ->where('data.description', $profile->description)
                    ->where('data.currency', $profile->currency)
                    ->where('data.balance', fn ($balance) => $balance == $profile->balance)
                    ->etc()
            );
    }
}
