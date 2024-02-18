<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\V1\CreateTravel;

use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\Concerns\WithValidationAssertions;
use Tests\Feature\FeatureTestCase;

class Test extends FeatureTestCase
{
    use WithValidationAssertions;

    /** @test */
    public function a_guest_cant_create_a_travel(): void
    {
        $this->createTravel()
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function an_admin_cant_create_a_travel_if_it_has_not_permission_to(): void
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
            'is_public_is_required' => [['isPublic' => null], 'isPublic'],
            'is_public_must_be_a_boolean' => [['isPublic' => 'string'], 'isPublic'],
            'moods_is_required' => [['moods' => null], 'moods'],
            'moods_must_be_an_array' => [['moods' => 'string'], 'moods'],
            'moods_culture_is_required' => [['moods' => ['culture' => null]], 'moods.culture.required'],
            'moods_culture_must_be_an_integer' => [['moods' => ['culture' => 'string']], 'moods.culture.int'],
            'moods_culture_must_be_at_least_0' => [['moods' => ['culture' => -1]], 'moods.culture.min'],
            'moods_culture_must_be_at_most_100' => [['moods' => ['culture' => 101]], 'moods.culture.max'],
            'moods_history_is_required' => [['moods' => ['history' => null]], 'moods.history.required'],
            'moods_history_must_be_an_integer' => [['moods' => ['history' => 'string']], 'moods.history.int'],
            'moods_history_must_be_at_least_0' => [['moods' => ['history' => -1]], 'moods.history.min'],
            'moods_history_must_be_at_most_100' => [['moods' => ['history' => 101]], 'moods.history.max'],
            'moods_nature_is_required' => [['moods' => ['nature' => null]], 'moods.nature.required'],
            'moods_nature_must_be_an_integer' => [['moods' => ['nature' => 'string']], 'moods.nature.int'],
            'moods_nature_must_be_at_least_0' => [['moods' => ['nature' => -1]], 'moods.nature.min'],
            'moods_nature_must_be_at_most_100' => [['moods' => ['nature' => 101]], 'moods.nature.max'],
            'moods_party_is_required' => [['moods' => ['party' => null]], 'moods.party.required'],
            'moods_party_must_be_an_integer' => [['moods' => ['party' => 'string']], 'moods.party.int'],
            'moods_party_must_be_at_least_0' => [['moods' => ['party' => -1]], 'moods.party.min'],
            'moods_party_must_be_at_most_100' => [['moods' => ['party' => 101]], 'moods.party.max'],
            'moods_relax_is_required' => [['moods' => ['relax' => null]], 'moods.relax.required'],
            'moods_relax_must_be_an_integer' => [['moods' => ['relax' => 'string']], 'moods.relax.int'],
            'moods_relax_must_be_at_least_0' => [['moods' => ['relax' => -1]], 'moods.relax.min'],
            'moods_relax_must_be_at_most_100' => [['moods' => ['relax' => 101]], 'moods.relax.max'],
            'name_is_required' => [['name' => null], 'name'],
            'name_must_be_a_string' => [['name' => 123], 'name'],
            'name_must_not_exceed_255_characters' => [['name' => str_repeat('a', 256)], 'name'],
        ];
    }

    /** @test */
    public function an_admin_can_create_a_travel(): void
    {
        $this->authAsAdminRole()
            ->createTravel()
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('travels', [
            'slug' => 'iceland-hunting-northern-lights',
            'is_public' => true,
            'name' => 'Iceland: Hunting Northern Lights',
            'description' => "Why visit Iceland in winter? Because it is between October and March that this land offers the spectacle of the Northern Lights, one of the most incredible and magical natural phenomena in the world, visible only near the earth's two magnetic poles. Come with us on WeRoad to explore this land of ice and fire, full of contrasts and natural variety, where the energy of waterfalls and geysers meets the peace of the fjords... And when the ribbons of light of the aurora borealis twinkle in the sky before our enchanted eyes, we will know that we have found what we were looking for.",
            'days' => 8,
            'moods->nature' => 100,
            'moods->relax' => 30,
            'moods->history' => 10,
            'moods->culture' => 20,
            'moods->party' => 10,
        ]);
    }

    private function createTravel(array $input = []): TestResponse
    {
        return $this->postJson('api/v1/travels', $this->validInput($input));
    }

    private function validInput(array $override): array
    {
        return array_merge([
            'isPublic' => true,
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
