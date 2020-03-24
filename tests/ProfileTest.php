<?php

namespace Navarr\Minecraft\Profile;

use GuzzleHttp\Client;
use Navarr\Minecraft\Profile;
use PHPUnit_Framework_TestCase;

class ProfileTest extends PHPUnit_Framework_TestCase
{
    /**
     * @medium
     */
    public function testFromUsername()
    {
        $this->mojangBuster();
        $profile = Profile::fromUsername('Navarr', $this->getClient());
        $this->asserts($profile);
    }

    /**
     * @medium
     */
    public function testFromUuid()
    {
        $this->mojangBuster();
        $profile = Profile::fromUuid('bd95beec116b4d37826c373049d3538b', $this->getClient());
        $this->asserts($profile);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Bad JSON from API: on username Nav"arr
     */
    // TODO: Current exception is stupid and ugly. Maybe we should use regex for this case?

    public function testBadUsername()
    {
        $this->mojangBuster();
        $profile = Profile::fromUsername('Nav"arr');
        $this->asserts($profile);
    }

    private function asserts(Profile $profile)
    {
        $this->assertEquals('bd95beec116b4d37826c373049d3538b', $profile->uuid);
        $this->assertEquals('Navarr', $profile->name);
        $this->assertTrue($profile->public);
        $this->assertEquals('http://textures.minecraft.net/texture/f2db938abac444ff315b95e9590184e0e2fe8941fdff559a4ab96cd54bcdd', $profile->capeUrl);
        $this->assertEquals('http://textures.minecraft.net/texture/91ebe08670c7af37a9ff439fb93290d75e35632dfbe3bf2ba3ac8494eb6e7', $profile->skinUrl);
        $this->assertNull($profile->namesHistory);
        
        $this->mojangBuster();
        $profile->previousUsernames();
        $this->assertEquals($profile->namesHistory[0]->name, 'Navarr');
    }

    /* To Prevent 429 - Barely works */

    private function mojangBuster()
    {
        // Mojang is something stupid like "600 per 10 minutes - so 1 per second and enforces it"
        sleep(3);
    }

    private function getClient()
    {
        $client = new Client();

        return new ApiClient($client);
    }
}
