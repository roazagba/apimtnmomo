<?php

namespace Roazagba\ApiMTNMomo\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Roazagba\ApiMTNMomo\SandboxUserProvisioning;
use Roazagba\ApiMTNMomo\Utilities\Helpers;

class CreateUserSandboxTest extends TestCase
{
    /** @test */
    public function it_creates_an_api_user_for_sandbox_environment()
    {
        $base_url = 'https://sandbox.momodeveloper.mtn.com/';
        $primary_key = '57ca5f1907074bf590090041688d781d';
        $callback_url = 'http://localhost:8000';

        $user = new SandboxUserProvisioning([
            'baseURL' => $base_url,
            'userID' => Helpers::uuid4(),
            'primaryKey' => $primary_key,
            'providerCallbackHost' => $callback_url
        ]);

        $this->assertIsArray($user->create());
    }
}
