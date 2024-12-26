<?php

namespace Database\Factories;

use App\Enum\APITypeEnum;
use App\Enum\MethodEnum;
use App\Models\Api;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ApiFactory extends Factory
{
    protected $model = Api::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'url' => $this->faker->url(),
            'service_type' => collect(APITypeEnum::cases())->random()->value,
            'method' => collect(MethodEnum::cases())->random()->value,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->faker->word(),
            ],
            'request' => fake()->word(),
            'expected_response' => $this->faker->word(),

            'certificate_id' => Certificate::inRandomOrder()->first() ?? Certificate::factory()->create(),
        ];
    }
}
