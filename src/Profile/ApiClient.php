<?php

namespace Navarr\Minecraft\Profile;

/**
 * Class ApiClient
 *
 * Used internally to query Mojang for the contents of the APIs.
 *
 * Exists for the sole purpose of replacing with a stub for testing.
 *
 * @package Navarr\Minecraft\Profile
 */
class ApiClient {
    const PROFILE_API = "https://sessionserver.mojang.com/session/minecraft/profile/%s";
    const UUID_API = "https://api.mojang.com/profiles";

    public function uuidApi($username) {

        $contextOptions = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => '{"agent":"minecraft","name":"'.str_replace('"', '\"', $username).'"}',
            ),
        );

        $json = file_get_contents(static::UUID_API, false, stream_context_create($contextOptions));
        if ($json === false || empty($json)) {
            throw new \RuntimeException('Invalid Username ('.$username.')');
        }

        return $json;
    }

    public function profileApi($uuid) {
        $return = file_get_contents(sprintf(static::PROFILE_API, $uuid));
        if ($return === false || empty($return)) {
            throw new \RuntimeException('Invalid UUID ('.$uuid.')');
        }

        return $return;
    }
}
