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
     * @expectedExceptionMessage Invalid Username (Nav"arr)
     */
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
        $this->assertEquals('http://textures.minecraft.net/texture/5112ebb7f5d7bdc5b57532af14408fbb757692ad81cc717e4c1faecdb9e3a2b5', $profile->skinUrl);
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
