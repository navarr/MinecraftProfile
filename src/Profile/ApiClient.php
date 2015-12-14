<?php

namespace Navarr\Minecraft\Profile;

use GuzzleHttp\ClientInterface;

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
        $this->client->setDefaultOption('verify', __DIR__.'/../../data/cacert.pem');
    }

    public function uuidApi($username)
    {
        $response = $this->client->post(static::UUID_API, [
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode([$username]),
        ])->json(['object' => true]);

        if (!$response) {
            throw new \RuntimeException('Bad JSON from API: on username '.$username);
        } elseif (isset($response->error)) {
            throw new \RuntimeException('Error from API: '.$response->error.' on username '.$username);
        }

        return $response;
    }

    public function profileApi($uuid)
    {
        $response = $this->client->get(sprintf(static::PROFILE_API, $uuid))->json(['object' => true]);

        if (!$response) {
            throw new \RuntimeException('Bad UUID '.$uuid);
        } elseif (isset($response->error)) {
            throw new \RuntimeException('Error from API: '.$response->error.' on UUID '.$uuid);
        }

        return $response;
    }
}
