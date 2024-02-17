<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\V1\CreateTravel;

use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\Concerns\WithValidationAssertions;
use Tests\Feature\FeatureTest;

class Test extends FeatureTest
{
    use WithValidationAssertions;

    /** @test */
    public function a_guest_cant_create_a_travel(): void
    {
        $this->createTravel()
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function an_admin_cant_create_if_it_has_not_permission_to(): void
    {
        $this->auth()
            ->createTravel()
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     *
     * @dataProvider invalidPayloads
     */
    public function to_create_a_travel_a_valid_payload_is_required(array $input, string $errorKey): void
    {
        $this->authAsAdminRole()
            ->createTravel($input)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertValidationErrorRule($errorKey);
    }

    public static function invalidPayloads(): array
    {
        return [
            'days_is_required' => [['days' => null], 'days'],
            'days_must_be_an_integer' => [['days' => 'string'], 'days'],
            'description_is_required' => [['description' => null], 'description'],
            'description_must_be_a_string' => [['description' => 123], 'description'],
            'is_public_is_required' => [['is_public' => null], 'is_public'],
            'is_public_must_be_a_boolean' => [['is_public' => 'string'], 'is_public'],
            'moods_is_required' => [['moods' => null], 'moods'],
            'moods_must_be_an_array' => [['moods' => 'string'], 'moods'],
            'moods.0.must_be_a_string' => [['moods' => [1]], 'moods.0.string'],
        ];
    }

    /** @test */
    public function create(): void
    {
        $this->authAsAdminRole()
            ->createTravel()
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function an_admin_with_role_admin_can_create_if_it_has_not_permission_to(): void
    {
        $this->authAsAdminRole()
            ->createTravel()
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_create_a_travel(): void
    {
        $this->authAsAdminRole()
            ->createTravel()
            ->assertStatus(Response::HTTP_CREATED);
    }

    private function createTravel(array $input = []): TestResponse
    {
        return $this->postJson('api/v1/travels', $this->validInput($input));
    }

    private function validInput(array $override): array
    {
        return array_merge([
            'slug' => 'iceland-hunting-northern-lights',
            'is_public' => true,
            'name' => 'Iceland: Hunting Northern Lights',
            'description' => "Why visit Iceland in winter? Because it is between October and March that this land offers the spectacle of the Northern Lights, one of the most incredible and magical natural phenomena in the world, visible only near the earth's two magnetic poles. Come with us on WeRoad to explore this land of ice and fire, full of contrasts and natural variety, where the energy of waterfalls and geysers meets the peace of the fjords... And when the ribbons of light of the aurora borealis twinkle in the sky before our enchanted eyes, we will know that we have found what we were looking for.",
            'days' => 8,
            'moods' => [
                'nature' => 100,
                'relax' => 30,
                'history' => 10,
                'culture' => 20,
                'party' => 10,
            ],
        ], $override);
    }
}
