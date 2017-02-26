<?php

namespace Navarr\Minecraft;

use Navarr\Minecraft\Adapter\AdapterInterface;
use Navarr\Minecraft\Adapter\GuzzleAdapter;

class MinecraftProfile
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * MinecraftProfile constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            $adapter = new GuzzleAdapter();
        }

        $this->adapter = $adapter;
    }

    /**
     * @param $uuid
     * @return Profile
     */
    public function getProfile($uuid)
    {
        $response = $this->adapter->getProfile($uuid);

        return new Profile($response);
    }

    /**
     * @param $username
     * @return Profile
     */
    public function getProfileViaUsername($username)
    {
        $uuid = $this->resolveUsernameToUuid($username);

        return $this->getProfile($uuid);
    }

    /**
     * @param $username
     * @return string
     */
    public function resolveUsernameToUuid($username)
    {
        $response = $this->adapter->getUuid($username);

        if (!is_array($response) || count($response) <> 1 || isset($response->error)) {
            $error = 'Unknown';

            if (isset($response->error)) {
                $error = $response->error;
            }

            throw new \RuntimeException(sprintf("Malformed response (%s)", $error));
        }

        return $response[0]->id;
    }
}
