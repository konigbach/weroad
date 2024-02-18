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
            'price_from_must_be_numeric' => [['priceFrom' => 'invalid-price'], 'priceFrom'],
            'price_to_must_be_numeric' => [['priceTo' => 'invalid-price'], 'priceTo'],
            'sort_by_must_be_one_of_price' => [['sortBy' => 'invalid-sort-by'], 'sortBy'],
            'sort_order_is_required_if_sort_by_is_present' => [['sortBy' => 'price'], 'sortOrder'],
            'sort_order_must_be_asc_or_desc' => [['sortBy' => 'price', 'sortOrder' => 'invalid-sort-order'], 'sortOrder'],
        ];
    }

    /** @test */
    public function a_user_can_list_tours_filtered_by_date_from(): void
    {
        $tour = Tour::factory()
            ->create([
                'ending_date' => '2024-01-10',
                'name' => 'Tour 1',
                'price' => 999,
                'starting_date' => '2024-01-01',
                'travel_id' => $this->travel->id,
            ]);

        $pastTour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'starting_date' => '2023-01-01',
            ]);

        $this->listTours(['dateFrom' => '2024-01-01'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data.tours')
            ->assertJson([
                'data' => [
                    'tours' => [
                        [
                            'id' => $tour->id,
                            'name' => 'Tour 1',
                            'price' => 999,
                            'startingDate' => '2024-01-01',
                            'endingDate' => '2024-01-10',
                        ],
                    ],
                ],
            ]);
    }

    /** @test */
    public function a_user_can_list_tours_filtered_by_date_to(): void
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

        $this->listTours(['dateTo' => '2024-01-01'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data.tours');
    }

    /** @test */
    public function a_user_can_list_tours_filtered_by_price_from(): void
    {
        $tourOnPrice = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'price' => '999',
            ]);

        $lowerTour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'price' => '998',
            ]);

        $this->listTours(['priceFrom' => '999'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data.tours');
    }

    /** @test */
    public function a_user_can_list_tours_filtered_by_price_to(): void
    {
        $tourAbovePrice = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'price' => 1000,
            ]);

        $tourOnPrice = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'price' => 999,
            ]);

        $tour2OnPrice = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'price' => 998,
            ]);

        $this->listTours(['priceTo' => '999'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data.tours');
    }

    /** @test */
    public function a_user_can_list_tours_sorted_by_starting_date_asc(): void
    {
        $lastTour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'starting_date' => '2024-01-02',
            ]);

        $firstTour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'starting_date' => '2024-01-01',
            ]);

        $this->listTours()
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.tours.0.id', $firstTour->id)
            ->assertJsonPath('data.tours.1.id', $lastTour->id);
    }

    /** @test */
    public function a_user_can_list_tours_sorted_by_price_asc(): void
    {
        $firstTour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'price' => 1,
            ]);

        $lastTour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'price' => 1000,
            ]);

        $this->listTours(['sortBy' => 'price', 'sortOrder' => 'asc'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.tours.0.id', $firstTour->id)
            ->assertJsonPath('data.tours.1.id', $lastTour->id);
    }

    /** @test */
    public function a_user_can_list_tours_sorted_by_price_desc(): void
    {
        $lastTour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'price' => 1,
            ]);

        $firstTour = Tour::factory()
            ->create([
                'travel_id' => $this->travel->id,
                'price' => 1000,
            ]);

        $this->listTours(['sortBy' => 'price', 'sortOrder' => 'desc'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.tours.0.id', $firstTour->id)
            ->assertJsonPath('data.tours.1.id', $lastTour->id);
    }

    private function listTours(array $params = []): TestResponse
    {
        return $this->getJson("public-api/v1/travels/{$this->travel->slug}/tours?".http_build_query($params));
    }
}
