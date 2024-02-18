<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\V1\CreateTour;

use App\Models\Travel;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\Concerns\WithValidationAssertions;
use Tests\Feature\FeatureTestCase;

class TestCase extends FeatureTestCase
{
    use WithValidationAssertions;

    private Travel $travel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travel = Travel::factory()->create();
    }

    /** @test */
    public function a_guest_cant_create_a_tour(): void
    {
        $this->createTour()
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function an_admin_cant_create_if_it_has_not_permission_to(): void
    {
        $this->auth()
            ->createTour()
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     *
     * @dataProvider invalidPayloads
     */
    public function to_create_a_tour_a_valid_payload_is_required(array $input, string $errorKey): void
    {
        $this->authAsAdminRole()
            ->createTour($input)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertValidationErrorRule($errorKey);
    }

    public static function invalidPayloads(): array
    {
        return [
            'ending_date_is_not_a_date' => [['endingDate' => 'not-a-date'], 'endingDate'],
            'ending_date_is_not_after_starting_date' => [['startingDate' => '2020-01-01', 'endingDate' => '2019-01-01'], 'endingDate'],
            'ending_date_is_required' => [['endingDate' => null], 'endingDate'],
            'name_is_not_a_string' => [['name' => 0], 'name'],
            'name_is_required' => [['name' => null], 'name'],
            'name_is_too_long' => [['name' => str_repeat('a', 256)], 'name'],
            'price_is_not_a_number' => [['price' => 'not-a-number'], 'price'],
            'price_is_required' => [['price' => null], 'price'],
            'starting_date_is_not_a_date' => [['startingDate' => 'not-a-date'], 'startingDate'],
            'starting_date_is_required' => [['startingDate' => null], 'startingDate'],
        ];
    }

    /** @test */
    public function an_admin_can_create_a_tour(): void
    {
        $this->authAsAdminRole()
            ->createTour()
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('tours', [
            'ending_date' => '2020-01-02',
            'name' => 'Tour name',
            'price' => 10_000,
            'starting_date' => '2020-01-01',
            'travel_id' => $this->travel->id,
        ]);
    }

    private function createTour(array $input = []): TestResponse
    {
        return $this->postJson("api/v1/travels/{$this->travel->slug}/create", $this->validInput($input));
    }

    private function validInput(array $override): array
    {
        return array_merge([
            'endingDate' => '2020-01-02',
            'name' => 'Tour name',
            'price' => 10_000,
            'startingDate' => '2020-01-01',
        ], $override);
    }
}
