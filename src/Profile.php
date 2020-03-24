<?php

namespace Navarr\Minecraft;

use GuzzleHttp\Client;
use Navarr\Minecraft\Profile\ApiClient;

/**
 * Class Profile.
 *
 * @property string $uuid
 * @property string $name
 * @property bool $public Whether or not the profile is public
 * @property string|null $capeUrl
 * @property string|null $skinUrl
 */
class Profile
{
    public $uuid;
    public $name;
    public $public = true;
    public $capeUrl = null;
    public $skinUrl = null;
    
    /** @var array|null */
    public $namesHistory = null;

    /**
     * @param string            $uuid
     * @param Profile\ApiClient $apiClient
     *
     * @throws \RuntimeException
     *
     * @return Profile
     */
    public static function fromUuid($uuid, ApiClient $apiClient = null)
    {
        if (is_null($apiClient)) {
            $apiClient = static::createApiClient();
        }

        $json = $apiClient->profileApi($uuid);

        if (is_null($json) || !isset($json->properties) || empty($json->properties) || !isset($json->properties[0]->value)) {
            throw new \RuntimeException('Error parsing JSON for UUID '.$uuid);
        }

        $properties = base64_decode($json->properties[0]->value);
        if ($properties === false) {
            throw new \RuntimeException('Error parsing base64 properties for UUID '.$uuid);
        }
        $properties = json_decode($properties);
        if (is_null($properties)) {
            throw new \RuntimeException('Error parsing JSON encoded properties for UUID '.$uuid);
        }

        $profile = new self();
        $profile->uuid = $json->id;
        $profile->name = $json->name;
        if (isset($properties->isPublic)) {
            $profile->public = $properties->isPublic;
        }
        if (isset($properties->textures)) {
            if (isset($properties->textures->SKIN) && isset($properties->textures->SKIN->url)) {
                $profile->skinUrl = $properties->textures->SKIN->url;
            }
            if (isset($properties->textures->CAPE) && isset($properties->textures->CAPE->url)) {
                $profile->capeUrl = $properties->textures->CAPE->url;
            }
        }

        return $profile;
    }

    /**
     * For gods' sakes, use a UUID.
     *
     * @param string            $username
     * @param Profile\ApiClient $apiClient
     *
     * @deprecated As Mojang commonly returns a 429 in response
     *
     * @throws \RuntimeException
     *
     * @return Profile
     */
    public static function fromUsername($username, ApiClient $apiClient = null)
    {
        if (is_null($apiClient)) {
            $apiClient = static::createApiClient();
        }

        $apiResult = $apiClient->uuidApi($username);

        $json = $apiResult;

        if (isset($json->error)) {
            throw new \RuntimeException('Mojang Error: '.$json->errorMessage);
        }

        if (empty($json) || !isset($json[0]->id)) {
            throw new \RuntimeException('Invalid Username ('.$username.')');
        }
        static::rateLimitBust();

        return static::fromUuid($json[0]->id);
    }

    /**
     * @return ApiClient
     */
    protected static function createApiClient()
    {
        return new ApiClient(new Client());
    }

    public function __get($var)
    {
        if (isset($this->{$var})) {
            return $this->{$var};
        }
    }

    public function __isset($var)
    {
        return isset($this->{$var});
    }

    private static function rateLimitBust()
    {
        sleep(1);
    }

    /**
     * Method for retrieving previous usernames of this profile.
     * When called multiple times it will returned first result unless forced by setting 
     * $force argument true.
     * 
     * @param bool              $force re-run retrieval instead of returning previous result
     * @param Profile\ApiClient $apiClient
     */
    public function previousUsernames(bool $force = false, ApiClient $apiClient = null)
    {
        if ( $this->namesHistory && !$force) {
            return $this->namesHistory;
        }

        if (is_null($apiClient)) {
            $apiClient = static::createApiClient();
        }

        $this->namesHistory = $apiClient->historyApi($this->uuid, 'names');

        return $this->namesHistory;
    }
}
