<?php

namespace Roazagba\ApiMTNMomo\Requests;

final class Request
{

    /**
     * Sends a GET request to a specified endpoint.
     *
     * This method uses cURL to send a GET request to the given endpoint with the specified headers
     * and returns the HTTP status code along with the decoded response.
     *
     * @param string $endpoint The URL to send the GET request to.
     * @param array $headers The headers to include in the request.
     * @return array An array containing the HTTP status code and the decoded response body.
     */
    public static function requestGet(string $endpoint, array $headers): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result  = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = json_decode($result);

        return [$http_status, $response];
    }

    /**
     * Sends a POST request to a specified endpoint.
     *
     * This method uses cURL to send a POST request to the given endpoint with the specified headers
     * and body (if provided), then returns the HTTP status code and decoded response.
     *
     * @param string $endpoint The URL to send the POST request to.
     * @param array $headers The headers to include in the request.
     * @param array|null $body The body to include in the POST request, which will be JSON-encoded. Can be null.
     * @return array An array containing the HTTP status code and the decoded response body.
     */
    public static function requestPost(string $endpoint, array $headers, ?array $body): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        if (isset($body)) {
            $body_json = json_encode($body);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body_json);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result  = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = json_decode($result);

        return [$http_status, $response];
    }
}
