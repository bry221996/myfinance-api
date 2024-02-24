<?php

namespace Tests\Feature\Controllers\WalletController;

use App\Models\Profile;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     *
     * @group wallets
     * @group wallets.index
     */
    public function user_list_profile_wallets(): void
    {
        $this->actingAs($user = User::factory()->create());

        $defaultProfile = $user->profiles->first();

        $wallets = Wallet::factory()
            ->count($count = $this->faker()->numberBetween(1, 5))
            ->create(['profile_id' => $defaultProfile->id]);

        $this->getJson('api/wallets', ['X-Profile-ID' => $defaultProfile->id])
            ->assertSuccessful()
            ->assertJson(
                fn (AssertableJson $json) => $json->has('meta')
                    ->has('links')
                    ->has(
                        'data',
                        $count,
                        fn (AssertableJson $json) => $json->where('id', $wallets->first()->id)
                            ->where('name', $wallets->first()->name)
                            ->where('currency', $wallets->first()->currency)
                            ->where('description', $wallets->first()->description)
                            ->etc()
                    )
            );
    }

    /**
     * @test
     *
     * @group wallets
     * @group wallets.index
     */
    public function user_cannot_list_other_user_profile_wallets(): void
    {
        $this->actingAs(User::factory()->create());

        $this->getJson('api/wallets', ['X-Profile-ID' => Profile::factory()->create()->id])
            ->assertForbidden();
    }
}
