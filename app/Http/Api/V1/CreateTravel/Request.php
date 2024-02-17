<?php

declare(strict_types=1);

namespace App\Http\Api\V1\CreateTravel;

use App\Http\Api\V1\CreateTravel\DTO\Moods;
use App\Models\Travel;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;

final class Request extends FormRequest
{
    public function authorize(Gate $gate): Response
    {
        return $gate->inspect('create', Travel::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'days' => 'required|integer',
            'description' => 'required|string',
            'is_public' => 'required|boolean',
            'moods' => 'required|array',
            'moods.0.culture' => 'required|int|min:0|max:100',
            'moods.history' => 'required|int|min:0|max:100',
            'moods.nature' => 'required|int|min:0|max:100',
            'moods.party' => 'required|int|min:0|max:100',
            'moods.relax' => 'required|int|min:0|max:100',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ];
    }

    public function slug(): string
    {
        return $this->string('slug')->value();
    }

    public function isPublic(): bool
    {
        return $this->boolean('is_public');
    }

    public function name(): string
    {
        return $this->string('name')->value();
    }

    public function description(): string
    {
        return $this->string('description')->value();
    }

    public function days(): int
    {
        return $this->integer('days');
    }

    public function moods(): Moods
    {
        /**
         * @var array{
         *     nature: int,
         *     relax: int,
         *     history: int,
         *     culture: int,
         *     party: int,
         * } $moods
         */
        $moods = $this->input('moods');

        return new Moods(
            nature: $moods['nature'],
            relax: $moods['relax'],
            history: $moods['history'],
            culture: $moods['culture'],
            party: $moods['party'],
        );
    }
}
