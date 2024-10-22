<?php

namespace Roazagba\ApiMTNMomo\Utilities;

use Exception;
use Roazagba\ApiMTNMomo\Requests\Request;

class TokenManager
{
    private $token_file = __DIR__ . '/token.json';
    const TOKEN_URI = "/token/";

    public function getToken($config, $product): string
    {
        $token_data = $this->loadToken();

        if ($token_data && $this->isTokenValid($token_data)) {
            return $token_data['access_token'];
        } else {
            $new_token_data = $this->fetchNewToken($config, $product);
            $this->saveToken($new_token_data);
            return $new_token_data['access_token'];
        }
    }

    private function isTokenValid($token_data): bool
    {
        return isset($token_data['expires_at']) && time() < $token_data['expires_at'];
    }

    private function fetchNewToken($config, $product): array
    {
        $url = $config->retrieveValue($product, 'host') . $product . static::TOKEN_URI;
        $primaryKey = $config->retrieveValue($product, 'PrimaryKey');
        $apiKeySecret = $config->retrieveValue($product, 'ApiKeySecret');
        $userID = $config->retrieveValue($product, 'userId');

        $headers = [
            "Authorization: Basic " . base64_encode($userID . ':' . $apiKeySecret),
            "Ocp-Apim-Subscription-Key: $primaryKey",
            'Content-Type: application/json'
        ];

        $response = Helpers::convertObjectArray(Request::requestPost($url, $headers, null));

        if ($response[0] != 200) {
            switch ($response[0]) {
                case 401:
                    throw new Exception("Unauthorized: " . $response[1]['error']);
                case 500:
                    throw new Exception($response[1]['error']);
                    break;
                default:
                    throw new Exception("Another error");
                    break;
            }
        }

        $token_data = $response[1];
        $token_data['expires_at'] = time() + 3600;

        return $token_data;
    }

    private function saveToken($token_data)
    {
        file_put_contents($this->token_file, json_encode($token_data));
    }

    private function loadToken()
    {
        if (file_exists($this->token_file)) {
            $token_data = json_decode(file_get_contents($this->token_file), true);
            return $token_data;
        }

        return null;
    }
}
