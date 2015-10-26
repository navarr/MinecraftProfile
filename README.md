## Minecraft Profile API in PHP

Usage is super simple and well documented.  

The Profile API in php uses composer to install. 

Be sure to include the composer autoloader to run the Profile API.

Here's a short example:

```php
use Navarr\Minecraft\Profile;

$profile = Profile::fromUuid('bd95beec116b4d37826c373049d3538b');
$history = Profile::alsoGet('history', $profile->uuid); //Returns a collection of usernames and the time they were last updated. 
$username = $profile->name;
$cape = $profile->capeUrl;
$skin = $profile->skinUrl;
```

History can also be acquired without getting the profile first by passing in the uuid. Use the `Profile::fromUsername` 
 function to get the uuid otherwise. 