<?php

namespace Navarr\Minecraft\Profile;

use Navarr\Minecraft\Profile;
use GuzzleHttp\Client;
use PHPUnit_Framework_TestCase;

class Tests extends PHPUnit_Framework_TestCase
{
    public function testFromUsername()
    {
        $this->mojangBuster();
        $profile = Profile::fromUsername('Navarr', $this->getClient());
        $this->asserts($profile);
    }

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
        Profile::fromUsername('Nav"arr');
    }

    private function asserts(Profile $profile) {
        $this->assertEquals('bd95beec116b4d37826c373049d3538b', $profile->uuid);
        $this->assertEquals('Navarr', $profile->name);
        $this->assertTrue($profile->public);
        $this->assertEquals('http://textures.minecraft.net/texture/95a2d2d94942966f743b84e4c262631978253979db673c2fbcc27dc3d2dcc7a7', $profile->capeUrl);
        $this->assertEquals('http://textures.minecraft.net/texture/91ebe08670c7af37a9ff439fb93290d75e35632dfbe3bf2ba3ac8494eb6e7', $profile->skinUrl);
    }

    /* To Prevent 429 */
    private function mojangBuster()
    {
        sleep(2);
    }

    private function getClient()
    {
        $client = new Client();
        $client->setDefaultOption('verify', __DIR__ . '/../data/cacert.pem');
        return new ApiClient($client);
    }
}
