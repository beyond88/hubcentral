<?php

namespace HubCentral\API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\TransferStats;

class HubCentralAPI
{

    /**
     * Indicates whether the sync feature is enabled or disabled.
     *
     * @var bool $is_enable True if the sync feature is enabled, false otherwise.
     */
    public $is_enable;

    /**
     * The base URL of the HubCentral API.
     *
     * @var string $base_url The base URL of the HubCentral API.
     */
    public $base_url = 'https://example.com';

    /**
     * HubCentralAPI constructor.
     * Initializes the class instance and retrieves settings from the database.
     */
    public function __construct()
    {
        $this->base_url = get_option('wc_settings_tab_storeconnect_base_url');
        $this->is_enable = get_option('wc_settings_tab_storeconnect_is_enable');
    }

    /**
     * Creates a new Guzzle HTTP client configured to communicate with the HubCentral API.
     *
     * @return \GuzzleHttp\Client The Guzzle HTTP client instance.
     */
    private function client()
    {

        return new Client([
            'base_uri' => $this->base_url . '/v1/',
            'timeout'  => 100,
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * Sends a GET request to the specified URL with optional query parameters.
     *
     * @param string $url The URL to send the GET request to.
     * @param array $params Optional query parameters for the request.
     * @return mixed The response body of the GET request.
     */
    public function get($url, array $params = [])
    {
        try {
            $response = $this->client()->get($url, [
                // 'auth'      => [ 0, $this->is_enable ],
                'query'     => $params,
                'on_stats' => function (TransferStats $stats) use (&$url) {
                    $url = $stats->getEffectiveUri();
                }
            ]);

            return $response->getBody();
        } catch (BadResponseException $ex) {
            $response = $ex->getResponse();
            error_log('====================API ERROR==================');
            error_log('URL: ' . $url);
            error_log((string) $response->getBody());
            die();
            var_dump((string) $response->getBody());
        }
    }

    /**
     * Sends a POST request to the specified URL with the provided form data.
     *
     * @param string $url The URL to send the POST request to.
     * @param array $formData The form data to include in the request.
     * @return mixed The response body of the POST request.
     */
    public function post($url, $formData)
    {
        try {
            $response = $this->client()->post($url, [
                // 'auth'      => [ 0, $this->is_enable ],
                'form_params'     => $formData,
                'on_stats' => function (TransferStats $stats) use (&$url) {
                    $url = $stats->getEffectiveUri();
                }
            ]);

            return $response->getBody();
        } catch (BadResponseException $ex) {
            $response = $ex->getResponse();
            error_log('====================API ERROR==================');
            error_log('URL: ' . $url);
            error_log((string) $response->getBody());
            die();
            var_dump((string) $response->getBody());
        }
    }

    /**
     * Sends a PUT request to the specified URL with the provided form data.
     *
     * @param string $url The URL to send the PUT request to.
     * @param array $formData The form data to include in the request.
     * @return mixed The response body of the PUT request.
     */
    public function put($url, $formData)
    {

        try {
            $response = $this->client()->put($url, [
                'auth'      => [0, $this->is_enable],
                // 'json'     => $formData,
                'on_stats' => function (TransferStats $stats) use (&$url) {
                    $url = $stats->getEffectiveUri();
                }
            ]);

            return $response->getBody();
        } catch (BadResponseException $ex) {
            $response = $ex->getResponse();
            error_log('====================API ERROR==================');
            error_log('URL: ' . $url);
            error_log((string) $response->getBody());
            var_dump((string) $response->getBody());
            die();
        }
    }
}
