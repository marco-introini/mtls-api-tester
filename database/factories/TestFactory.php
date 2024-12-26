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
        $api = Api::inRandomOrder()->first() ?? Api::factory()->create();
        return [
            'api' => $api,
            'called_url' => $api->url(),
            'request' => $api->request,
            'request_raw' => [
                'headers' => $api->headers,
                'body' => $api->request,
                'method' => $api->method->value,
            ],
            'request_timestamp' => Carbon::now(),
            'request_certificates' => [
                $api->certificate->public_cert
            ],
            'response' => fake()->sentence(),
            'response_raw' => [],
            'response_timestamp' => Carbon::now()->addSeconds(random_int(1,20)),
            'response_expected' => $api->expected_response(),
            'response_ok' => fake()->boolean(),
        ];
    }
}
