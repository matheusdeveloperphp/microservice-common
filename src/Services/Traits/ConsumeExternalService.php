<?php

namespace Matheusdeveloperphp\MicroserviceCommon\Services\Traits;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ConsumeExternalService
{

    protected $url;
    protected $token = null;

    /**
     * @param array $headers
     * @return PendingRequest
     */
    protected function headers(array $headers = []): PendingRequest
    {
        $default = [
            'Accept' => 'application/json',
        ];

        if (!empty($this->token)) {
            $default['Authorization'] = $this->token; // ou "Bearer {$this->token}" se for bearer
        }

        $finalHeaders = array_merge($default, $headers);

        return Http::timeout(10)->retry(2, 200)->withHeaders($finalHeaders);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $formParams
     * @param array $headers
     * @return mixed
     */
    public function request(string $method, string $endpoint, array $formParams = [], array $headers = [])
    {
        return $this->headers($headers)->{$method}($this->url . $endpoint, $formParams);
    }

}
