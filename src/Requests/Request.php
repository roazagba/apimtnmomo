<?php

namespace Roazagba\ApiMTNMomo\Requests;

final class Request
{
    public static function requestGet(string $endpoint, array $headers)
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

    public static function requestPost(string $endpoint, array $headers, ?array $body)
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
