# Minecraft Profile API in PHP

## Installation

MinecraftProfile uses Composer. For more information about composer, read the
[Getting Started](https://getcomposer.org/doc/00-intro.md) document.

To install with composer:

```bash
composer install navarr/minecraft-profile
```

## Usage

### Creating An Instance

You can create an instance with the default `GuzzleAdapter` or with a class that implements the `AdapterInterface`.

**Note:** It's highly recommended that you use caching! See ***Caching*** for more info.
 
```php
use Navarr\Minecraft\MinecraftProfile;

// Basic Usage
$minecraftProfile = new MinecraftProfile();

$profile = $minecraftProfile->getProfile('bd95beec116b4d37826c373049d3538b'); // ->getProfile(uuid);
$username = $profile->getName();
```

```php
use Navarr\Minecraft\MinecraftProfile;
use Navarr\Minecraft\Adapter\GuzzleAdapter;

// Custom adapter
$adapter = new GuzzleAdapter();
$minecraftProfile = new MinecraftProfile($adapter);
```

### Static Method Calls

A static call will create a new `MinecraftProfile` instance on every call. You
can optionally pass an adapter as a second parameter

```php
use Navarr\Minecraft\Profile;

$profile = Profile::fromUuid('bd95beec116b4d37826c373049d3538b');
$username = $profile->getName();
$cape = $profile->getCapeUrl();
$skin = $profile->getSkinUrl();
```

## Caching

It's highly recommended that you use a caching mechanism, as Mojang's rate limiting is very aggressive.

This could be as simple as saving responses, and checking if you have it already, before requesting it again.

### Caching with Guzzle

*This is a little complicated to setup, but is easy to use after, and works well when put together.*

This example show how to use `kevinrob/guzzle-cache-middleware` with the `GuzzleAdapter`. See
<https://github.com/Kevinrob/guzzle-cache-middleware> for more info on how to implement the `CacheMiddleware`.

#### Returning a cache header

Mojang's API doesn't return a `Cache-Contorl` header in their responses. So we will have to add a header after
guzzle has fired the request and got a response.

To do this, we just need to create a simple middleware for your Guzzle client:

```php
function getCacheHeaderMiddleware()
{
    return function (callable $handler) {
        return function (RequestInterface $request, array $options) use ($handler) {
            $promise = $handler($request, $options);
            return $promise->then(
                function (ResponseInterface $response) {
                    // Only send a cache header back on successful responses
                    if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                        return $response->withHeader("Cache-Control", "max-age=300");
                    }

                    return $response;
                }
            );
        };
    };
}
```

#### Putting it together with the CacheMiddleware

This code shows you how to put together: the middleware we created to inject a response header, and the cache
middleware, with the Guzzle adapter.

```php
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Navarr\Minecraft\MinecraftProfile;
use Navarr\Minecraft\Adapter\GuzzleAdapter;

// Create default HandlerStack
$stack = HandlerStack::create();

// Create a CacheMiddleware
$cacheMiddleware = new CacheMiddleware();

// For username requests, we will have to tell the middleware to
// cache POST request responses. Skip this if you're only using uuid
$cacheMiddleware->setHttpMethods([
  'GET' => true,
  'POST' => true
]);

// Add the caching middleware to the top of the stack
$stack->push(new CacheMiddleware(), 'cache');

// Add our custom response header middleware after the cache middleware
$stack->after('cache', getCacheHeaderMiddleware());

// Initialize the client with the handler option
$client = new Client(['handler' => $stack]);

// Pass the client to a new adapter
$adapter = new GuzzleAdapter($client);

// Initalise MinecraftProfile
$minecraftProfile = new MinecraftProfile($adapter);
```
