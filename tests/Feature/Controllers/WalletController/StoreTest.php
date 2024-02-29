<?php

namespace Feature\Controllers\WalletController;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public static function wallet_form_validation(): array
    {
        return [
            ['name',  ''],
            ['balance', ''],
            ['balance', 'string'],
            ['currency', ''],
            ['description', ''],
        ];
    }

    /**
     * @test
     */
    public function user_can_create_wallet()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $defaultProfile = $user->profiles->first();

        $data = Wallet::factory()
            ->make(['profile_id' => $defaultProfile->id]);

        $this->postJson(route('wallets.store'), $data->toArray(), ['X-Profile-ID' => $defaultProfile->id])
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $data->name,
                'balance' => $data->balance,
                'currency' => $data->currency,
                'description' => $data->description,
            ]);

        $this->assertDatabaseHas('wallets', $data->toArray());
    }

    /**
     * @test
     */
    public function unauthenticated_user_cannot_create_wallet()
    {
        $this->postJson(route('wallets.store'))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function user_cannot_create_wallet_without_profile()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $this->postJson(route('wallets.store'))
            ->assertForbidden();
    }

    /**
     * @test
     *
     * @dataProvider wallet_form_validation
     */
    public function user_cannot_create_wallet_with_validation_error($attribute, $value)
    {
        Sanctum::actingAs($user = User::factory()->create());

        $defaultProfile = $user->profiles->first();

        $data = Wallet::factory()
            ->make(['profile_id' => $defaultProfile->id]);

        $this->postJson(route('wallets.store'), [
            ...$data->toArray(),
            $attribute => $value,
        ],
            ['X-Profile-ID' => $defaultProfile->id]
        )
            ->assertUnprocessable()
            ->assertJsonValidationErrors($attribute);
    }
}
