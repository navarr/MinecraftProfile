# Minecraft Profile API in PHP

## Usage

Usage is super simple and well documented.  Here's a short example:

```php
use Navarr\Minecraft\Profile;

$profile = Profile::fromUuid('bd95beec116b4d37826c373049d3538b');
$username = $profile->name;
$cape = $profile->capeUrl;
$skin = $profile->skinUrl;
$names_history = $profile->namesHistory;
```

## Installation

MinecraftProfile uses Composer.  For more information about composer, read the [Getting Started](https://getcomposer.org/doc/00-intro.md) document.

To install with composer:

> `composer install navarr/minecraft-profile`
