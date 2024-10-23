<?php

namespace Roazagba\ApiMTNMomo;

use Roazagba\ApiMTNMomo\MTNMoMoConfig;
use Exception;

class MTNMoMo
{
    /**
     * The configuration object for MTN MoMo API.
     *
     * @var MTNMoMoConfig
     */
    protected $config;

    /**
     * The product name for MTN MoMo API.
     *
     * @var string|null
     */
    protected $product = null;


    /**
     * MTNMoMo constructor.
     *
     * Initializes the MTN MoMo class with a configuration object.
     *
     * @param MTNMoMoConfig $config The configuration object containing API credentials and settings.
     */
    public function __construct(MTNMoMoConfig $config)
    {
        $this->config = $config;
    }


    /**
     * Verifies the response for exceptions based on the HTTP status code.
     *
     * This method checks the HTTP status code in the response and throws an exception
     * if it indicates an error such as bad request, unauthorized access, conflict, or internal server error.
     *
     * @param array $response The response array containing the HTTP status code and response body.
     * @throws Exception If the response indicates an error (e.g., 400, 401, 409, 500).
     * @return void
     */
    public function verifException($response)
    {
        switch ($response[0]) {
            case 400:
                throw new Exception("Bad request, e.g. invalid data was sent in the request.");
                break;
            case 401:
                throw new Exception("Unauthorized");
            case 409:
                throw new Exception("Conflict, duplicated reference id" . print_r($response[1]));
                break;
            case 500:
                throw new Exception("Internal Server Error" . print_r($response[1]));
                break;
            default:
                throw new Exception("Another error");
                break;
        }
    }

    /*
     * Retrieves an access token from the MTN MoMo API (commented out).
     *
     * This method is responsible for retrieving an access token using the credentials from the configuration.
     * It makes a POST request to the token endpoint and handles possible errors based on the response status.
     * 
     * @return string The access token.
     * @throws Exception If the response indicates an error (e.g., 401, 500).
     *
     public function getToken() : string
    {
        $url = $this->config->retrieveValue($this->product, 'host') . $this->product . static::TOKEN_URI;
        $primaryKey = $this->config->retrieveValue($this->product, 'PrimaryKey');
        $apiKeySecret = $this->config->retrieveValue($this->product, 'ApiKeySecret');
        $userID = $this->config->retrieveValue($this->product, 'userId');

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

        return $response[1];
    } */
}
