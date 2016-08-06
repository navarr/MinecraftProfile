<?php

namespace Navarr\Minecraft\Profile;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

/**
 * Class ApiClient.
 *
 * Used internally to query Mojang for the contents of the APIs.
 *
 * Exists for the sole purpose of replacing with a stub for testing.
 */
class ApiClient
{
    const PROFILE_API = 'https://sessionserver.mojang.com/session/minecraft/profile/%s';
    const UUID_API = 'https://api.mojang.com/profiles/minecraft';

    /**
     * Guzzle client instance.
     *
     * @var ClientInterface
     */
    public $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    private function getOptions()
    {
        return ['verify' => __DIR__.'/../../data/cacert.pem'];
    }

    public function uuidApi($username)
    {
        $request = new Request(
            'POST',
            static::UUID_API,
            ['Content-Type' => 'application/json'],
            json_encode([$username])
        );

        $response = $this->client->send($request, $this->getOptions());
        $response = @json_decode($response->getBody());

        if (!$response) {
            throw new \RuntimeException('Bad JSON from API: on username '.$username.' - '.json_last_error_msg());
        } elseif (isset($response->error)) {
            throw new \RuntimeException('Error from API: '.$response->error.' on username '.$username);
        }

        return $response;
    }

    public function profileApi($uuid)
    {
        $request = new Request('GET', sprintf(static::PROFILE_API, $uuid));
        $httpResponse = $this->client->send($request, $this->getOptions());
        $response = @json_decode($httpResponse->getBody());

        if ($httpResponse->getStatusCode() != 200) {
            throw new \RuntimeException('Bad UUID '.$uuid);
        } elseif (!$response) {
            throw new \RuntimeException('Bad JSON from API: on UUID '.$uuid.' - '.json_last_error_msg());
        } elseif (isset($response->error)) {
            throw new \RuntimeException('Error from API: '.$response->error.' on UUID '.$uuid);
        }

        return $response;
    }
}
