<?php

namespace Navarr\Minecraft\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

class GuzzleAdapter implements AdapterInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * GuzzleAdapter constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client = null)
    {
        if (!$client) {
            $client = new Client();
        }

        $this->client = $client;
    }

    protected function sendRequest(Request $request)
    {
        return $this->client->send($request);
    }

    /**
     * @param $uuid
     * @return \stdClass The decoded JSON response from the server
     */
    public function getProfile($uuid)
    {
        $request = new Request(
            'GET',
            sprintf(self::URL_PROFILE . '/%s', $uuid)
        );

        $response = $this->sendRequest($request);

        return \GuzzleHttp\json_decode($response->getBody());
    }

    /**
     * @param string|\string[] $username
     * @return \stdClass The decoded JSON response from the server
     */
    public function getUuid($username)
    {
        if (!is_array($username) && !is_scalar($username)) {
            throw new \InvalidArgumentException("Username must be of type string or string[]");
        }

        if (is_scalar($username)) {
            if (strlen($username) < 1) {
                throw new \InvalidArgumentException("Username must be at least of length 1");
            }

            $username = [$username];
        }

        $request = new Request(
            'POST',
            self::URL_UUID,
            [
                'Content-Type' => 'application/json'
            ],
            json_encode($username)
        );

        $response = $this->sendRequest($request);

        return \GuzzleHttp\json_decode($response->getBody());
    }
}