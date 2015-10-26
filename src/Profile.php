<?php

namespace Navarr\Minecraft;

use Navarr\Minecraft\Profile\ApiClient;
use GuzzleHttp\Client;

/**
 * Class Profile
 * @package Navarr\Minecraft\Profile
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


    /**
     * @param string $uuid
     * @param Profile\ApiClient $apiClient
     * @throws \RuntimeException
     * @return Profile
     */
    public static function fromUuid($uuid, ApiClient $apiClient = null)
    {
        if (is_null($apiClient)) {
            $apiClient = static::createApiClient();
        }

        $json = $apiClient->profileApi($uuid);

        if (is_null($json) || !isset($json->properties) || empty($json->properties) || !isset($json->properties[0]->value)) {
            throw new \RuntimeException('Error parsing JSON for UUID ' . $uuid);
        }

        $properties = base64_decode($json->properties[0]->value);
        if ($properties === false) {
            throw new \RuntimeException('Error parsing base64 properties for UUID ' . $uuid);
        }
        $properties = json_decode($properties);
        if (is_null($properties)) {
            throw new \RuntimeException('Error parsing JSON encoded properties for UUID ' . $uuid);
        }

        $profile = new Profile();
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
     * @param string $username
     * @param Profile\ApiClient $apiClient
     * @throws \RuntimeException
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
            throw new \RuntimeException('Mojang Error: ' . $json->errorMessage);
        }

        if (empty($json->profiles) || !isset($json->profiles[0]->id)) {
            throw new \RuntimeException('Invalid Username (' . $username . ')');
        }

        return static::fromUuid($json->profiles[0]->id);
    }
    
    /**
     * This function returns 
     * 
     * @param string $case
     * @param string $uuid
     * @throws \RuntimeException
     * @return array
     */
    public function alsoGet($case, $uuid)
    {
    	$apiClient = static::createApiClient();
    	$collection = array();
    	switch($case) {
    		case	'history':
    			$apiResult = $apiClient->historyApi($uuid);
    			
    			$json = $apiResult;
    
    			if (isset($json->error)) {
    				throw new \RuntimeException('Mojang Error: ' . $json->errorMessage);
    			}
    			
    			foreach($json as $piece) {
    				$obj = new \stdClass();
    				$obj->name = $piece->name;
    				$obj->last_updated = (!isset($piece->changedToAt)) ? 'Current' : $piece->changedToAt;
    				$collection[] = $obj;
    			}
    		break;
    	}
    	
    	return $collection;
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
        return null;
    }

    public function __isset($var)
    {
        return isset($this->{$var});
    }
}
