<?php

namespace Roazagba\ApiMTNMomo;

use Roazagba\ApiMTNMomo\Utilities\Helpers;
use Exception;

final class MTNMoMoConfig
{
    /**
     * The base URL for the MTN MoMo API.
     *
     * @var string
     */
    public $baseURL;

    /**
     * The currency for transactions.
     *
     * @var string
     */
    public $currency;

    /**
     * The target environment (e.g., sandbox or mtnbenin or ...).
     *
     * @var string
     */
    public $targetEnvironment;

    /**
     * The product type.
     *
     * @var string
     */
    public $product;

    /**
     * The configuration array holding API keys and other credentials.
     *
     * @var array
     */
    public $config;


    /**
     * MTNMoMoConfig constructor.
     *
     * This constructor initializes the configuration object with values retrieved from the environment or configuration files.
     * It uses a helper class to assign the attributes and store them in the config property.
     */
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


    /**
     * Retrieves a specific value from the configuration based on the product and config key.
     *
     * This method filters the configuration based on the product and retrieves the corresponding
     * key-value pair from the config array. If the key does not exist, it throws an exception.
     *
     * @param string|null $product The product name, used to retrieve product-specific configuration values.
     * @param string $configKey The configuration key to retrieve (e.g., 'PrimaryKey', 'ApiKeySecret').
     * @return string The value of the configuration key.
     * @throws Exception If the specified key does not exist in the configuration.
     */
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
