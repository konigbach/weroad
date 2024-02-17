<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\V1\CreateTour;

use App\Models\Travel;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\Concerns\WithValidationAssertions;
use Tests\Feature\FeatureTest;

class Test extends FeatureTest
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
            'name_is_required' => [['name' => null], 'name'],
            'name_is_not_a_string' => [['name' => 0], 'name'],
            'name_is_too_long' => [['name' => str_repeat('a', 256)], 'name'],
            'starting_date_is_required' => [['starting_date' => null], 'starting_date'],
            'starting_date_is_not_a_date' => [['starting_date' => 'not-a-date'], 'starting_date'],
            'ending_date_is_required' => [['ending_date' => null], 'ending_date'],
            'ending_date_is_not_a_date' => [['ending_date' => 'not-a-date'], 'ending_date'],
            'ending_date_is_not_after_starting_date' => [['starting_date' => '2020-01-01', 'ending_date' => '2019-01-01'], 'ending_date'],
            'price_is_required' => [['price' => null], 'price'],
            'price_is_not_a_number' => [['price' => 'not-a-number'], 'price'],
        ];
    }

    /** @test */
    public function an_admin_can_create_a_tour(): void
    {
        $this->authAsAdminRole()
            ->createTour()
            ->assertStatus(Response::HTTP_CREATED);
    }

    private function createTour(array $input = []): TestResponse
    {
        return $this->postJson("api/v1/travels/{$this->travel->slug}/create", $this->validInput($input));
    }

    private function validInput(array $override): array
    {
        return array_merge([
            'travel_id' => Travel::factory()->create()->id,
            'name' => 'Tour name',
            'starting_date' => '2020-01-01',
            'ending_date' => '2020-01-02',
            'price' => 10_000,
        ], $override);
    }
}
