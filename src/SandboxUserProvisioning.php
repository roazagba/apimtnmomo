<?php

namespace Roazagba\ApiMTNMomo;

use Roazagba\ApiMTNMomo\Utilities\Helpers;
use Roazagba\ApiMTNMomo\Requests\Request;
use Exception;
use Roazagba\ApiMTNMomo\Products\MTNMoMoCollection;
use Roazagba\ApiMTNMomo\MTNMoMoConfig;

final class SandboxUserProvisioning
{
    public $config;
    private const CREATE_USER_URI = '/v1_0/apiuser';

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function create(): array
    {
        //$userID = Helpers::uuid4();
        $userID = $this->config['userID'];
        $primaryKey = $this->config['primaryKey'];
        $baseURL = $this->config['baseURL'];

        $headers1 = [
            "X-Reference-Id: $userID",
            "Ocp-Apim-Subscription-Key: $primaryKey",
            'Content-Type: application/json'
        ];

        $body = [
            'providerCallbackHost' => $this->config['providerCallbackHost']
        ];

        $response0 = Request::requestPost($baseURL . self::CREATE_USER_URI, $headers1, $body);

        if ($response0[0] != 201) {
            switch ($response0[0]) {
                case 400:
                    throw new Exception("Bad request, e.g. invalid data was sent in the request.");
                    break;
                case 401:
                    throw new Exception($response0[1]->message);
                    break;
                case 409:
                    throw new Exception("Conflict, duplicated user id");
                    break;
                default:
                    break;
            }
        } else {
            $headers2 = [
                "Ocp-Apim-Subscription-Key: $primaryKey",
                'Content-Type: application/json'
            ];
            $response1 = Request::requestPost($baseURL . self::CREATE_USER_URI . "/$userID/apikey", $headers2, null);

            if ($response1[0] != 201) {
                switch ($response1[0]) {
                    case 400:
                        throw new Exception("Bad request, e.g. invalid data was sent in the request.");
                        break;
                    case 404:
                        throw new Exception("Not found, reference id not found or closed in sandbox");
                        break;
                    case 500:
                        throw new Exception("Internal error. Check log for information.");
                        break;
                    default:
                        break;
                }
            } else {
                $response2 = Request::requestGet($baseURL . self::CREATE_USER_URI . "/$userID", $headers2);
                if ($response2[0] == 200) {
                    $infos = [
                        'baseURL' => $baseURL,
                        'userID' => $userID,
                        'primaryKey' => $primaryKey,
                        'apiKeySecret' => $response1[1]->apiKey,
                        'targetEnvironment' => $response2[1]->targetEnvironment,
                        'providerCallbackHost' => $response2[1]->providerCallbackHost
                    ];
                    return $infos;
                }
            }
        }
    }
}
