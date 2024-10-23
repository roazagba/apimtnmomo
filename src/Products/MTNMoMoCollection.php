<?php

namespace Roazagba\ApiMTNMomo\Products;

use Roazagba\ApiMTNMomo\MTNMoMo;
use Exception;
use Roazagba\ApiMTNMomo\Requests\Request;
use Roazagba\ApiMTNMomo\Utilities\{Helpers, TokenManager};

class MTNMoMoCollection extends MTNMoMo
{
    /**
     * The product name for this collection service.
     *
     * @var string
     */
    public $product = 'collection';

    private const REQUEST_TO_PAY_URI = '/v1_0/requesttopay';
    private const ACCOUNT_BALANCE_URI = '/v1_0/account/balance';
    private const GET_BASIC_USER_INFO_URI = '/v1_0/accountholder';


    /**
     * Retrieve the base URL for the collection API.
     *
     * @return string
     */
    private function getUrl(): string
    {
        return $this->config->retrieveValue($this->product, 'host') . $this->product;
    }

    /**
     * Retrieve the access token required for API requests.
     *
     * @return string
     */
    private function getToken(): string
    {
        $token = new TokenManager;

        return $token->getToken($this->config, $this->product);
    }


    /**
     * Creates a new transaction to request payment.
     *
     * @param array $params Array containing 'amount', 'referenceExternalID', 'numberMoMo', 'description', and 'note'.
     * @param array $custom_params Additional custom parameters for the transaction (optional).
     * @return array Returns an array with 'transactionId' and any custom parameters.
     * @throws Exception If required keys are missing or the response indicates an error.
     */
    public function createTransaction(array $params, array $custom_params = []): array
    {
        $required_keys = ['amount', 'referenceExternalID', 'numberMoMo', 'description', 'note'];

        $missing_keys = array_diff($required_keys, array_keys($params));

        if (!empty($missing_keys)) {
            throw new Exception("The missing keys are : " . implode(', ', $missing_keys));
        }

        $currency = $this->config->retrieveValue($this->product, 'currency');
        $access_token = $this->getToken();
        $xReferenceId = Helpers::uuid4();
        $primary_key = $this->config->retrieveValue($this->product, 'primaryKey');
        $callback = $this->config->retrieveValue($this->product, 'callbackUrl');
        $target = $this->config->retrieveValue($this->product, 'target');

        $headers = [
            "Ocp-Apim-Subscription-Key: $primary_key",
            "X-Reference-Id: $xReferenceId",
            "Authorization: Bearer $access_token",
            "X-Target-Environment: $target",
            //"X-Callback-Url: $callback",
            'Content-Type: application/json'
        ];

        $body = [
            'amount' => $params['amount'],
            'currency' => $currency,
            'externalId' => $params['referenceExternalID'],
            'payer' => [
                "partyIdType" => "MSISDN",
                "partyId" => $params['numberMoMo']
            ],
            "payerMessage" => $params['description'],
            "payeeNote" => $params['note']
        ];

        $response = Helpers::convertObjectArray(Request::requestPost($this->getUrl() . self::REQUEST_TO_PAY_URI, $headers, $body));

        if ($response[0] != 202) {
            $this->verifException($response);
        }

        return ['transactionId' => $xReferenceId, 'customParams' => $custom_params];
    }


    /**
     * Retrieve the status of a transaction by its reference ID.
     *
     * @param string $xReferenceId The reference ID of the transaction.
     * @return array Returns an array with the transaction details.
     * @throws Exception If the transaction reference ID is invalid or the request fails.
     */
    public function getTransaction(string $xReferenceId): array
    {
        if (!isset($xReferenceId) && empty($xReferenceId)) {
            throw new Exception("Transaction reference ID is invalid");
        }

        $access_token = $this->getToken();
        $primary_key = $this->config->retrieveValue($this->product, 'primaryKey');
        $target = $this->config->retrieveValue($this->product, 'target');

        $headers = [
            "Ocp-Apim-Subscription-Key: $primary_key",
            "Authorization: Bearer $access_token",
            "X-Target-Environment: $target",
            'Content-Type: application/json'
        ];

        $response = Helpers::convertObjectArray(Request::requestGet($this->getUrl() . self::REQUEST_TO_PAY_URI . '/' . $xReferenceId, $headers));

        if ($response[0] != 200) {
            $this->verifException($response);
        }

        return $response[1];
    }


    /**
     * Retrieve the account balance for the collection service.
     *
     * @param string|null $currency Optional currency parameter for specific balances.
     * @return array Returns an array with the account balance details.
     * @throws Exception If the request fails.
     */
    public function getAccountBalance(?string $currency = null): array
    {
        $access_token = $this->getToken();
        $primary_key = $this->config->retrieveValue($this->product, 'primaryKey');
        $target = $this->config->retrieveValue($this->product, 'target');

        $headers = [
            "Ocp-Apim-Subscription-Key: $primary_key",
            "Authorization: Bearer $access_token",
            "X-Target-Environment: $target",
            'Content-Type: application/json'
        ];

        $url = isset($currency) ? $this->getUrl() . self::ACCOUNT_BALANCE_URI . '/' . $currency : $this->getUrl() . self::ACCOUNT_BALANCE_URI;

        $response = Helpers::convertObjectArray(Request::requestGet($url, $headers));

        if ($response[0] != 200) {
            $this->verifException($response);
        }

        return $response[1];
    }


    /**
     * Retrieve basic user information by the user's MoMo number.
     *
     * @param string $numberMoMo The MoMo number of the user.
     * @return array Returns an array with the basic user information.
     * @throws Exception If the request fails.
     */
    public function getBasicUserInfo(string $numberMoMo): array
    {
        $access_token = $this->getToken();
        $primary_key = $this->config->retrieveValue($this->product, 'primaryKey');
        $target = $this->config->retrieveValue($this->product, 'target');

        $headers = [
            "Ocp-Apim-Subscription-Key: $primary_key",
            "Authorization: Bearer $access_token",
            "X-Target-Environment: $target",
            'Content-Type: application/json'
        ];

        $url = $this->getUrl() . self::GET_BASIC_USER_INFO_URI . '/MSISDN/' . $numberMoMo . '/basicuserinfo';

        $response = Helpers::convertObjectArray(Request::requestGet($url, $headers));

        if ($response[0] != 200) {
            $this->verifException($response);
        }

        return $response[1];
    }
}
