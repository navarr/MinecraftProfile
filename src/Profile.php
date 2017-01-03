<?php

namespace Navarr\Minecraft;

use Navarr\Minecraft\Adapter\AdapterInterface;

class Profile
{
    /**
     * @var string
     */
    public $uuid;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $properties = [];

    /**
     * @param string $uuid
     * @param AdapterInterface $adapter
     * @return Profile
     */
    public static function fromUuid($uuid, AdapterInterface $adapter = null)
    {
        $minecraftProfile = new MinecraftProfile($adapter);

        return $minecraftProfile->getProfile($uuid);
    }

    /**
     * @param string $username
     * @param AdapterInterface $adapter
     * @return Profile
     * @internal param ApiClient $apiClient
     */
    public static function fromUsername($username, AdapterInterface $adapter = null)
    {
        $minecraftProfile = new MinecraftProfile($adapter);

        return $minecraftProfile->getProfileViaUsername($username);
    }

    /**
     * Profile constructor.
     * @param \stdClass|null $data
     */
    public function __construct(\stdClass $data = null)
    {
        if ($data) {
            $this->uuid = $data->id;
            $this->name = $data->name;

            if (isset($data->properties)) {
                foreach ($data->properties as $property) {
                    $value = null;
                    $base64Value = base64_decode($property->value);

                    if ($base64Value !== false) {
                        $value = \GuzzleHttp\json_decode($base64Value);
                    }

                    $this->properties[$property->name] = $value;
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param $property
     * @return bool
     */
    public function hasProperty($property)
    {
        return array_key_exists($property, $this->properties);
    }

    /**
     * @param $property
     * @return array|mixed|null
     */
    public function getProperty($property)
    {
        if ($this->hasProperty($property)) {
            return $this->properties[$property];
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getSkinUrl()
    {
        if ($textures = $this->getProperty('textures')) {
            if (isset($textures->textures->SKIN) && isset($textures->textures->SKIN->url)) {
                return $textures->textures->SKIN->url;
            }
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getCapeUrl()
    {
        if ($textures = $this->getProperty('textures')) {
            if (isset($textures->textures->CAPE) && isset($textures->textures->CAPE->url)) {
                return $textures->textures->CAPE->url;
            }
        }

        return null;
    }
}
