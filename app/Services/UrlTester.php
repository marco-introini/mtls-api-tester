<?php

namespace App\Services;

use App\Enum\MethodEnum;
use App\Enum\APITypeEnum;
use App\Models\Test;
use App\Models\Url;
use CurlHandle;
use DOMDocument;
use Illuminate\Support\Facades\Storage;

class UrlTester
{
    private CurlHandle $curlHandle;

    public string $response;

    public string $beginTime;

    public string $endTime;

    public array $curlInfo;

    public string $requestHeaders;

    public string $serverCertificates = "";

    public string $requestCertificate = "";

    public function __construct(
        public Url $url
    ) {
    }

    private function setGenericCurlOptions(): void
    {
        curl_setopt($this->curlHandle, CURLOPT_URL, $this->url->url);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);

        // change this to have the headers in output
        curl_setopt($this->curlHandle, CURLOPT_HEADER, false);
        // we need CURLINFO_HEADER_OUT to have access on request headers
        curl_setopt($this->curlHandle, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->curlHandle, CURLOPT_VERBOSE, true);

        // tls 1.2
        curl_setopt($this->curlHandle, CURLOPT_SSLVERSION, 6);
    }

    private function setCertificates(): void
    {
        // CA Certificate
        if (!is_null($this->url?->certificate?->ca_certificate)) {
            curl_setopt(
                $this->curlHandle,
                CURLOPT_CAINFO,
                Storage::disk('certificates')->path($this->url->certificate->ca_certificate)
            );
            curl_setopt($this->curlHandle, CURLOPT_CERTINFO, true);
        }

        // Authentication keys (must be provided both public and private key
        if (!is_null($this->url->certificate->private_key) && !is_null($this->url->certificate->public_key)) {
            curl_setopt(
                $this->curlHandle,
                CURLOPT_SSLKEY,
                Storage::disk('certificates')->path($this->url->certificate->private_key)
            );
            curl_setopt(
                $this->curlHandle,
                CURLOPT_SSLCERT,
                Storage::disk('certificates')->path($this->url->certificate->public_key)
            );
            // use this for Password protected private Key
            //curl_setopt($this->curlHandle, CURLOPT_SSLCERTPASSWD, "");

            $this->requestCertificate = $this->url->certificate->name;
        }

        // to disable CA verification
        //curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, false);
    }

    public function executeTest(): string
    {
        $this->curlHandle = curl_init();

        $this->setGenericCurlOptions();
        if ($this->url->useCertificates()) {
            $this->setCertificates();
        }

        // default headers
        $headers = [
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'Content-length: '.strlen($this->url->request),
        ];

        if ($this->url->service_type == APITypeEnum::SOAP) {
            $headers[] = 'Content-type: text/xml;charset="utf-8"';
            $headers[] = 'Accept: text/xml';
            if (!is_null($this->url->soap_action)) {
                $headers[] = 'SOAPAction: '.$this->url->soap_action;
            }
        }
        if ($this->url->service_type == APITypeEnum::REST) {
            $headers[] = 'Content-type: application/json;charset="utf-8"';
            $headers[] = 'Accept: application/json';
        }

        curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $headers);

        if ($this->url->method != MethodEnum::POST) {
            curl_setopt($this->curlHandle, CURLOPT_CUSTOMREQUEST, $this->url->method->value);
        } else {
            curl_setopt($this->curlHandle, CURLOPT_POST, true);
        }
        curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $this->url->request);

        $this->beginTime = now();
        $result = curl_exec($this->curlHandle);
        $this->endTime = now();

        curl_close($this->curlHandle);

        if (!$result) {
            $this->response = curl_error($this->curlHandle);

            return $this->response;
        }

        if ($this->url->service_type == APITypeEnum::SOAP) {
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = true;
            $dom->formatOutput = true;
            $dom->loadXML($result);
            $this->response = $dom->saveXML();
        } else {
            $this->response = json_encode(json_decode($result), JSON_PRETTY_PRINT);
        }

        $this->curlInfo = curl_getinfo($this->curlHandle);

        $this->requestHeaders = curl_getinfo($this->curlHandle,CURLINFO_HEADER_OUT);
        $this->serverCertificates = json_encode(curl_getinfo($this->curlHandle,CURLINFO_CERTINFO));

        return $this->response;
    }

    private function checkSuccess(): bool
    {
        return str_contains($this->response, $this->url->expected_response);
    }

    public function saveResultToTestModel(): void
    {
        Test::create([
            'url_id' => $this->url->id,
            'request' => $this->url->request,
            'request_date' => $this->beginTime,
            'response' => $this->response,
            'response_date' => $this->endTime,
            'response_time' => curl_getinfo($this->curlHandle, CURLINFO_TOTAL_TIME_T),
            'response_ok' => $this->checkSuccess(),
            'curl_info' => $this->curlInfo,
            'called_url' => $this->url->url,
            'expected_response' => $this->url->expected_response,
            'server_certificates' => $this->serverCertificates,
            'request_certificates' => $this->requestCertificate,
            'request_headers' => $this->requestHeaders,
        ]);
    }
}
