<?php

declare(strict_types=1);

namespace Tests\Feature\Http\PublicApi\V1\ListTours;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\Concerns\WithValidationAssertions;
use Tests\Feature\FeatureTestCase;

class Test extends FeatureTestCase
{
    use WithValidationAssertions;

    private Travel $travel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travel = Travel::factory()->create();
    }

    /**
     * @test
     *
     * @dataProvider invalidPayloads
     */
    public function to_list_tours_a_valid_payload_is_required(array $input, string $errorKey): void
    {
        $this->listTours($input)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertValidationErrorRule($errorKey);
    }

    public static function invalidPayloads(): array
    {
        return [
            'date_from_must_be_a_date' => [['dateFrom' => 'invalid-date'], 'dateFrom'],
            'date_to_must_be_a_date' => [['dateTo' => 'invalid-date'], 'dateTo'],
            'date_to_must_be_after_date_from' => [['dateFrom' => '2024-01-01', 'dateTo' => '2024-01-01'], 'dateTo'],
            'price_from_must_be_numeric' => [['priceFrom' => 'invalid-price'], 'priceFrom'],
            'price_to_must_be_numeric' => [['priceTo' => 'invalid-price'], 'priceTo'],
            'price_to_must_be_greater_than_or_equal_to_price_from' => [['priceFrom' => 100, 'priceTo' => 50], 'priceTo'],
        ];
    }

    /** @test */
    public function a_user_can_list_tours_filter_by_date_from(): void
    {
        $tour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'starting_date' => '2024-01-01',
            ]);

        $pastTour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'starting_date' => '2023-01-01',
            ]);

        $this->listTours(['dateFrom' => '2024-01-01'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'tours' => [
                    [
                        'id' => $tour->id,
                    ],
                ],
            ])->assertJsonMissing([
                'tours' => [
                    [
                        'id' => $pastTour->id,
                    ],
                ],
            ]);
    }

    /** @test */
    public function a_user_can_list_tours_filter_by_date_to(): void
    {
        $tour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'starting_date' => '2024-02-01',
            ]);

        $pastTour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'starting_date' => '2024-01-01',
            ]);

        $this->listTours(['dateTo' => '2024-02-01'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'tours' => [
                    [
                        'id' => $tour->id,
                    ],
                ],
            ])->assertJsonMissing([
                'tours' => [
                    [
                        'id' => $pastTour->id,
                    ],
                ],
            ]);
    }

    private function listTours(array $params = []): TestResponse
    {
        return $this->getJson("public-api/v1/travels/{$this->travel->slug}?".http_build_query($params));
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
