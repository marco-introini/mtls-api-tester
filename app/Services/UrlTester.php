<?php

namespace App\Services;

use App\Enum\APITypeEnum;
use App\Models\Api;
use App\Models\Test;
use DOMDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UrlTester
{
    public function __construct(
        public Api $api
    ) {
    }

    public function executeTest(): Test
    {
        $beginTime = now();

        $headers = $this->api->headers;
        $body = $this->api->request;
        $contentType = match ($this->api->service_type) {
            APITypeEnum::SOAP => 'text/xml',
            APITypeEnum::REST => 'application/json',
        };
        $url = $this->api->url;
        $verb = $this->api->method->value;

        $rawRequest = [
            'method' => $verb,
            'url' => $url,
            'headers' => $headers,
            'body' => $body,
        ];

        try {
            $httpResponse = Http::withOptions([
                'cert' => self::createTempStreamFromString($this->api->certificate->public_cert,
                    $this->api->certificate->private_key),
                'verify' => self::createTempStreamFromString($this->api->certificate->ca_cert),
            ])->withBody($body, $contentType)
                ->withHeaders($headers)
                ->send($verb, $url);

            $result = $httpResponse->body();

            $rawResponse = [
                'status' => $httpResponse->status(),
                'headers' => $httpResponse->headers(),
                'body' => $httpResponse->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Errore nella connessione mTLS:', ['message' => $e->getMessage()]);

            return Test::create([
                'api_id' => $this->api->id,

            ]);

        }

        $endTime = now();

        if ($this->api->service_type == APITypeEnum::SOAP) {
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = true;
            $dom->formatOutput = true;
            $dom->loadXML($result);
            $response = $dom->saveXML();
        } else {
            $response = json_encode(json_decode($result), JSON_PRETTY_PRINT);
        }


        return $this->response;
    }

    /**
     * Funzione per creare uno stream temporaneo da una stringa.
     *
     * @param  string  $content  La stringa da convertire in stream.
     * @param  string|null  $key  La stringa della chiave privata, opzionale.
     * @return array|string Il percorso di uno stream temporaneo o array (cert & key).
     */
    private static function createTempStreamFromString(string $content, ?string $key = null): array|string
    {
        // Crea uno stream temporaneo.
        $tmpCert = fopen('php://temp', 'r+');
        fwrite($tmpCert, $content);
        rewind($tmpCert);

        // fir private key, return both private key and public cert
        if ($key !== null) {
            $tmpKey = fopen('php://temp', 'r+');
            fwrite($tmpKey, $key);
            rewind($tmpKey);

            return [$tmpCert, $tmpKey];
        }

        return $tmpCert;
    }

}
