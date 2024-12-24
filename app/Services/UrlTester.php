<?php

namespace App\Services;

use App\Enum\APITypeEnum;
use App\Models\Test;
use App\Models\Api;
use DOMDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UrlTester
{
    protected string $response;
    protected string $beginTime;
    protected string $endTime;
    protected array $curlInfo;
    protected array $requestHeaders;
    protected string $serverCertificates = "";
    protected string $requestCertificate = "";

    public function __construct(
        public Api $api
    ) {
    }

    public function executeTest(): string
    {
        $this->beginTime = now();
        try {
            $response = Http::withOptions([
                'cert' => self::createTempStreamFromString($this->api->certificate->public_cert,
                    $this->api->certificate->private_key),
                'verify' => self::createTempStreamFromString($this->api->certificate->ca_cert),
            ])->withBody($this->api->request, match ($this->api->service_type) {
                APITypeEnum::SOAP => 'text/xml',
                APITypeEnum::REST => 'application/json',
            })
                ->send($this->api->method->value, $this->api->url);

            $result = $response->body();

        } catch (\Exception $e) {
            Log::error('Errore nella connessione mTLS:', ['message' => $e->getMessage()]);
            return $e->getMessage();
        }

        $this->endTime = now();

        if ($this->api->service_type == APITypeEnum::SOAP) {
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = true;
            $dom->formatOutput = true;
            $dom->loadXML($result);
            $this->response = $dom->saveXML();
        } else {
            $this->response = json_encode(json_decode($result), JSON_PRETTY_PRINT);
        }

        $this->curlInfo = curl_getinfo($this->curlHandle);

        $this->requestHeaders = curl_getinfo($this->curlHandle, CURLINFO_HEADER_OUT);
        $this->serverCertificates = json_encode(curl_getinfo($this->curlHandle, CURLINFO_CERTINFO));

        return $this->response;
    }

    private function checkSuccess(): bool
    {
        return str_contains($this->response, $this->api->expected_response);
    }

    public function saveResultToTestModel(): void
    {
        Test::create([
            'url_id' => $this->api->id,
            'request' => $this->api->request,
            'request_date' => $this->beginTime,
            'response' => $this->response,
            'response_date' => $this->endTime,
            'response_time' => curl_getinfo($this->curlHandle, CURLINFO_TOTAL_TIME_T),
            'response_ok' => $this->checkSuccess(),
            'curl_info' => $this->curlInfo,
            'called_url' => $this->api->url,
            'expected_response' => $this->api->expected_response,
            'server_certificates' => $this->serverCertificates,
            'request_certificates' => $this->requestCertificate,
            'request_headers' => $this->requestHeaders,
        ]);
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

        // Se è specificata una chiave privata, restituisci entrambi come array.
        if ($key !== null) {
            $tmpKey = fopen('php://temp', 'r+');
            fwrite($tmpKey, $key);
            rewind($tmpKey);

            return [$tmpCert, $tmpKey]; // Cert e Key insieme
        }

        return $tmpCert;
    }

}
