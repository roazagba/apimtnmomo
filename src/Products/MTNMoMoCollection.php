<?php

namespace Roazagba\ApiMTNMomo\Products;

use Roazagba\ApiMTNMomo\MTNMoMo;
use Exception;
use Roazagba\ApiMTNMomo\Requests\Request;
use Roazagba\ApiMTNMomo\Utilities\{Helpers, TokenManager};

class MTNMoMoCollection extends MTNMoMo
{
    public $product = 'collection';

    private const REQUEST_TO_PAY_URI = '/v1_0/requesttopay';
    private const ACCOUNT_BALANCE_URI = '/v1_0/account/balance';
    private const GET_BASIC_USER_INFO_URI = '/v1_0/accountholder';

    private function getUrl(): string
    {
        return $this->config->getValue($this->product, 'host') . $this->product;
    }

    private function getToken(): string
    {
        $token = new TokenManager;

        return $token->getToken($this->config, $this->product);
    }

    public function createTransaction(array $params): string
    {
        $required_keys = ['amount', 'referenceExternalID', 'numberMoMo', 'description', 'note'];

        $missing_keys = array_diff($required_keys, array_keys($params));

        if (!empty($missing_keys)) {
            throw new Exception("The missing keys are : " . implode(', ', $missing_keys));
        }



        $currency = $this->config->getValue($this->product, 'currency');
        $access_token = $this->getToken();
        $xReferenceId = Helpers::uuid4();
        $primary_key = $this->config->getValue($this->product, 'primaryKey');
        $callback = $this->config->getValue($this->product, 'callbackUrl');
        $target = $this->config->getValue($this->product, 'target');

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

        return $xReferenceId;
    }

    public function getTransaction(string $xReferenceId): array
    {
        if (!isset($xReferenceId) && empty($xReferenceId)) {
            throw new Exception("Transaction reference ID is invalid");
        }

        $access_token = $this->getToken();
        $primary_key = $this->config->getValue($this->product, 'primaryKey');
        $target = $this->config->getValue($this->product, 'target');

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

    public function getAccountBalance(?string $currency = null): array
    {
        $access_token = $this->getToken();
        $primary_key = $this->config->getValue($this->product, 'primaryKey');
        $target = $this->config->getValue($this->product, 'target');

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

    public function getBasicUserInfo(string $numberMoMo): array
    {
        $access_token = $this->getToken();
        $primary_key = $this->config->getValue($this->product, 'primaryKey');
        $target = $this->config->getValue($this->product, 'target');

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
