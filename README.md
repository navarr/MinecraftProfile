## Minecraft Profile API in PHP

Usage is super simple and well documented.  Here's a short example:

    <?php

    use Navarr\Minecraft\Profile;

    $profile = Profile::fromUuid('bd95beec116b4d37826c373049d3538b');
    $username = $profile->name;
    $cape = $profile->capeUrl;
    $skin = $profile->skinUrl;
