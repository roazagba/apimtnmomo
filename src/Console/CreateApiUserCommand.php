<?php

namespace Roazagba\ApiMTNMomo\Console;

use Illuminate\Console\Command;
use Roazagba\ApiMTNMomo\SandboxUserProvisioning;
use Roazagba\ApiMTNMomo\Utilities\Helpers;

class CreateApiUserCommand extends Command
{
    protected $signature = 'momo:create-api-user {baseurl} {primarykey} {callbackurl}';
    protected $description = 'Crée un utilisateur API Momo dans l\'environnement sandbox';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $base_url = $this->argument('baseurl');
        $primary_key = $this->argument('primarykey');
        $callback_url = $this->argument('callbackurl');

        $this->info('Creating API user with the following details:');
        $this->info("Base URL: $base_url");
        $this->info("Primary Key: $primary_key");
        $this->info("Callback URL: $callback_url");

        try {
            $user = new SandboxUserProvisioning([
                'baseURL' => $base_url,
                'userID' => Helpers::uuid4(),
                'primaryKey' => $primary_key,
                'providerCallbackHost' => $callback_url
            ]);

            $result = $user->create();
            $this->info("API User created successfully.");
            $this->info(print_r($result, true));
        } catch (\Exception $e) {
            $this->error("Error creating API user: " . $e->getMessage());
        }
    }
}
