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
    private const PRODUCTS = ['collection', 'disbursement', 'remittance'];

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
        $this->config = get_object_vars($helpers->massAssignAttributes($array));
    }

    public function checkCredentials($prod)
    {
        $cfg = $this->config;
        $keys_cfg = array_keys($cfg);
        if (!in_array($prod, self::PRODUCTS)) {
            throw new Exception('Invalid product. Product must be ' . implode(' or ', self::PRODUCTS));
        }

        $missRequi = [];
        $requirements = ['host', 'currency', 'target', 'callbackUrl', $prod . 'PrimaryKey', $prod . 'ApiKeySecret', $prod . 'UserId'];

        foreach ($requirements as $requirement) {
            if (!in_array($requirement, $keys_cfg)) {
                $missRequi[] = $requirement;
            }
        }

        if (count($missRequi) != 0) {
            throw new Exception("The following credentials are missing: " . implode(', ', $missRequi));
        }

        return $this;
    }

    public function getValue(?string $product = "", $configKey = ""): string
    {
        $filtered_nocoll = array_filter(array_keys($this->config), function ($item) use ($product) {
            return strpos($item, $product) !== 0;
        });

        if (in_array($configKey, $filtered_nocoll)) {
            $key = $configKey;
        } else {
            $key = strtolower($product) . ucfirst($configKey);
        }

        if (!in_array($key, array_keys($this->config))) {
            throw new Exception("$key does not exist in config credetentials");
        }

        return $this->config[$key];
    }
}
