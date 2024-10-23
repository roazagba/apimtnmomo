<?php

namespace Roazagba\ApiMTNMomo;

use Roazagba\ApiMTNMomo\Requests\Request;
use Exception;

final class SandboxUserProvisioning
{
    /**
     * Configuration array containing user details, API keys, and base URL.
     *
     * @var array
     */
    public $config;


    /**
     * The URI for creating a new user in the sandbox environment.
     */
    private const CREATE_USER_URI = '/v1_0/apiuser';


    /**
     * SandboxUserProvisioning constructor.
     *
     * Initializes the class with the provided configuration array.
     *
     * @param array $config The configuration array containing user credentials, base URL, and other required details.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }


    /**
     * Creates a new user in the MTN MoMo Sandbox and generates an API key.
     *
     * This method sends a POST request to the sandbox environment to create a new user
     * using the provided configuration details. If successful, it generates an API key and
     * retrieves user information.
     *
     * @return array Returns an array containing user ID, primary key, API key, target environment, and callback host.
     * @throws Exception If the response indicates an error (e.g., 400, 401, 409).
     */
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
