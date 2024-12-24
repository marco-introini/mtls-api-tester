<?php

namespace App\Services;

use App\DTO\KeyPair;
use Illuminate\Support\Carbon;

class CertificateUtilityService
{
    public static function generateCertificate(string $commonName, ?Carbon $expirationDate): KeyPair
    {
        $config = [
            'digest_alg' => config('x509-generator.cert_alg'),
            'private_key_bits' => config('x509-generator.cert_keybit'),
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'dn' => [
                'countryName' => config('x509-generator.cert_country'),
                'stateOrProvinceName' => config('x509-generator.cert_state'),
                'localityName' => config('x509-generator.cert_city'),
                'organizationName' => config('x509-generator.cert_organization_name'),
                'organizationalUnitName' => config('x509-generator.cert_organization_unit'),
                'commonName' => $commonName,
                'emailAddress' => config('x509-generator.cert_email'),
                'serialNumber' => self::generateSerialNumber(),
            ],
        ];

        $privateKeyResource = openssl_pkey_new($config);
        openssl_pkey_export($privateKeyResource, $privateKey);

        $csr = openssl_csr_new($config['dn'], $privateKeyResource);

        if (is_null($expirationDate)) {
            $validityDays = 365;
        } else {
            $validityDays = round($expirationDate->diffInDays(Carbon::now(), true));
        }
        $certificateResource = openssl_csr_sign($csr, null, $privateKeyResource, $validityDays, $config);

        openssl_x509_export($certificateResource, $certificate);

        return new KeyPair(
            $privateKey,
            $certificate);
    }

    public static function generateSerialNumber($length = 16): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $serial = '';
        for ($i = 0; $i < $length; $i++) {
            $index = random_int(0, strlen($characters) - 1);
            $serial .= $characters[$index];
        }

        return $serial;
    }

}
