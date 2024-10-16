<?php

namespace Roazagba\ApiMTNMomo;

use Roazagba\ApiMTNMomo\MTNMoMoConfig;
use Roazagba\ApiMTNMomo\Requests\Request;
use Roazagba\ApiMTNMomo\Utilities\Helpers;
use Exception;

class MTNMoMo
{
    protected $config;
    protected $product = null;

    public function __construct(MTNMoMoConfig $config)
    {
        $this->config = $config;
    }

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

    /* public function getToken() : string
    {
        $url = $this->config->getValue($this->product, 'host') . $this->product . static::TOKEN_URI;
        $primaryKey = $this->config->getValue($this->product, 'PrimaryKey');
        $apiKeySecret = $this->config->getValue($this->product, 'ApiKeySecret');
        $userID = $this->config->getValue($this->product, 'userId');

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
