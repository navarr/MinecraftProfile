<?php

namespace Navarr\Minecraft\Profile\Tests;

use Navarr\Minecraft\Profile\ApiClient;

class BadMockApiClient extends ApiClient
{
    public function uuidApi($username = "Navarr")
    {
        return json_decode('{"profiles":[],"size":0}');
    }

    public function profileApi($uuid = "bd95beec116b4d37826c373049d3538b")
    {
        return false;
    }
}
