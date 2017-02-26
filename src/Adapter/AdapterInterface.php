<?php

namespace Navarr\Minecraft\Adapter;

interface AdapterInterface
{
    const URL_PROFILE = 'https://sessionserver.mojang.com/session/minecraft/profile';
    const URL_UUID = 'https://api.mojang.com/profiles/minecraft';

    /**
     * @param string $uuid
     * @return \stdClass The decoded JSON response from the server
     */
    public function getProfile($uuid);

    /**
     * @param string|string[] $username
     * @return \stdClass The decoded JSON response from the server
     */
    public function getUuid($username);
}
