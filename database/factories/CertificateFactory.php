<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Services\CertificateUtilityService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition(): array
    {
        $fakeCommonName = fake()->name();
        $fakeCertificate = CertificateUtilityService::generateCertificate(
            commonName: $fakeCommonName,
            expirationDate: Carbon::now()->addDays(30),
        );

        return [
            'ca_certificate' => $this->faker->word(),
            'name' => $fakeCommonName,
            'private_key' => $fakeCertificate->privateKey,
            'public_cert' => $fakeCertificate->certificate,
        ];
    }
}
