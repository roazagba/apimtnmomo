<?php

namespace Roazagba\ApiMTNMomo\Utilities;

use Exception;
use Roazagba\ApiMTNMomo\Requests\Request;

class TokenManager
{
    /**
     * The file path where the token is stored.
     *
     * @var string
     */
    private $token_file = __DIR__ . '/token.json';


    /**
     * The URI for requesting a token.
     */
    const TOKEN_URI = "/token/";


    /**
     * Retrieves the access token.
     *
     * This method first checks if a valid token exists. If the token is still valid, it returns it.
     * Otherwise, it fetches a new token, saves it, and returns the new token.
     *
     * @param object $config The configuration object for retrieving API credentials.
     * @param string $product The product or service name for which the token is requested.
     * @return string The access token.
     * @throws Exception If fetching the token fails.
     */
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


    /**
     * Checks if the token is still valid.
     *
     * This method checks if the token has an expiration time and if the current time is before the expiration time.
     *
     * @param array $token_data The token data array containing the token and expiration time.
     * @return bool True if the token is valid, false otherwise.
     */
    private function isTokenValid($token_data): bool
    {
        return isset($token_data['expires_at']) && time() < $token_data['expires_at'];
    }


    /**
     * Fetches a new access token.
     *
     * This method sends a request to the API to obtain a new access token using the provided configuration and product.
     *
     * @param object $config The configuration object for retrieving API credentials.
     * @param string $product The product or service name for which the token is requested.
     * @return array The new token data, including the token and expiration time.
     * @throws Exception If the request fails with an unauthorized or server error.
     */
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


    /**
     * Saves the token data to a file.
     *
     * This method stores the token data in a JSON file for later use.
     *
     * @param array $token_data The token data to save.
     * @return void
     */
    private function saveToken($token_data)
    {
        file_put_contents($this->token_file, json_encode($token_data));
    }


    /**
     * Loads the token data from a file.
     *
     * This method reads the token data from a JSON file if it exists.
     *
     * @return array|null The token data array, or null if the file does not exist.
     */
    private function loadToken()
    {
        if (file_exists($this->token_file)) {
            $token_data = json_decode(file_get_contents($this->token_file), true);
            return $token_data;
        }

        return null;
    }
}
