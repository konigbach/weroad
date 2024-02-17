<?php

declare(strict_types=1);

namespace App\Http\Api\V1\UpdateTravel;

use App\Http\Api\V1\CreateTravel\DTO\Moods;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;

final class Request extends FormRequest
{
    public function authorize(): Response
    {
        return Response::allow();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'days' => 'required|integer',
            'description' => 'required|string',
            'moods.*' => 'string',
            'moods.*.culture' => 'required|int|min:0|max:100',
            'moods.*.history' => 'required|int|min:0|max:100',
            'moods.*.nature' => 'required|int|min:0|max:100',
            'moods.*.party' => 'required|int|min:0|max:100',
            'moods.*.relax' => 'required|int|min:0|max:100',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:travels,slug',
        ];
    }

    public function slug(): string
    {
        return $this->routeString('slug');
    }

    public function name(): string
    {
        return $this->string('name')->toString();
    }

    public function description(): string
    {
        return $this->string('description')->toString();
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
