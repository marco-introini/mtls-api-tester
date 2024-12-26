<?php

namespace Database\Factories;

use App\Models\Api;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TestFactory extends Factory
{
    protected $model = Test::class;

    public function definition(): array
    {
        return [
            'api' => Api::inRandomOrder()->first() ?? Api::,
            'called_url' => $this->faker->url(),
            'request' => $this->faker->word(),
            'request_raw' => $this->faker->words(),
            'request_timestamp' => Carbon::now(),
            'request_certificates' => $this->faker->word(),
            'response' => $this->faker->word(),
            'response_raw' => $this->faker->words(),
            'response_timestamp' => Carbon::now(),
            'response_expected' => $this->faker->word(),
            'response_ok' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'curl_info' => $this->faker->word(),
            'response_time' => Carbon::now(),
            'server_certificates' => $this->faker->word(),
            'expected_response' => $this->faker->word(),
            'request_headers' => $this->faker->word(),
            'request_date' => Carbon::now(),

            'api_id' => Api::factory(),
        ];
    }
}
