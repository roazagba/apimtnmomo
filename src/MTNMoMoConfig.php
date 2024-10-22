<?php

namespace Roazagba\ApiMTNMomo;

use Roazagba\ApiMTNMomo\Utilities\Helpers;
use Exception;

final class MTNMoMoConfig
{
    public $baseURL;
    public $currency;
    public $targetEnvironment;

    public $product;

    public $config;

    public function __construct()
    {
        $array = [
            'host' => config('mtnmomo.host'),
            'currency' => config('mtnmomo.currency'),
            'target' => config('mtnmomo.target'),

            'callbackUrl' => config('mtnmomo.callback_url'),
            'collectionApiKeySecret' => config('mtnmomo.collection.api_key_secret'),
            'collectionPrimaryKey' => config('mtnmomo.collection.primary_key'),
            'collectionUserId' => config('mtnmomo.collection.user_id')
        ];

        $helpers = new Helpers;
        $this->config = get_object_vars($helpers->assignAttributes($array));
    }

    public function retrieveValue(?string $product = "", $configKey = ""): string
    {
        $filtered_nocoll = array_filter(array_keys($this->config), function ($item) use ($product) {
            return strpos($item, $product) !== 0;
        });

        $key = in_array($configKey, $filtered_nocoll) ? $configKey : strtolower($product) . ucfirst($configKey);

        if (!array_key_exists($key, $this->config)) {
            throw new Exception("$key does not exist in config credetentials");
        }

        return $this->config[$key];
    }
}
