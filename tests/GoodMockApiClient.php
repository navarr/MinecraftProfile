<?php

namespace Navarr\Minecraft\Profile\Tests;

use Navarr\Minecraft\Profile\ApiClient;

class GoodMockApiClient extends ApiClient
{
    public function uuidApi($username = "Navarr")
    {
        return '{"profiles":[{"id":"bd95beec116b4d37826c373049d3538b","name":"Navarr"}],"size":1}';
    }

    public function profileApi($uuid = "bd95beec116b4d37826c373049d3538b")
    {
        return '{"id":"bd95beec116b4d37826c373049d3538b","name":"Navarr","properties":[{"name":"textures","value":"eyJ0aW1lc3RhbXAiOjEzOTM3OTMwNjk1MDUsInByb2ZpbGVJZCI6ImJkOTViZWVjMTE2YjRkMzc4MjZjMzczMDQ5ZDM1MzhiIiwicHJvZmlsZU5hbWUiOiJOYXZhcnIiLCJpc1B1YmxpYyI6dHJ1ZSwidGV4dHVyZXMiOnsiQ0FQRSI6eyJ1cmwiOiJodHRwOi8vdGV4dHVyZXMubWluZWNyYWZ0Lm5ldDo4MDgwL3RleHR1cmUvOTVhMmQyZDk0OTQyOTY2Zjc0M2I4NGU0YzI2MjYzMTk3ODI1Mzk3OWRiNjczYzJmYmNjMjdkYzNkMmRjYzdhNyJ9LCJTS0lOIjp7InVybCI6Imh0dHA6Ly90ZXh0dXJlcy5taW5lY3JhZnQubmV0OjgwODAvdGV4dHVyZS81MTEyZWJiN2Y1ZDdiZGM1YjU3NTMyYWYxNDQwOGZiYjc1NzY5MmFkODFjYzcxN2U0YzFmYWVjZGI5ZTNhMmI1In19fQ==","signature":"eeSzYntpyHxwKs98QLyBChQUhDIpdxm0ucSWS6NtjqMZ8hvw1yP5tBZHPdXhkebw7f/PSOkUeLuIbx80ZaffeRcXFxTnGL/GIsC7Pbgf5/RwMpTvibfOhmSWUK93x7DQ+VpJaBsQwjFaxgPZ2nlO8c+FSWZJEX6Id9NPI7qlqhGuvRKV9kcboMzbeQlq7cdHJZ+ss6elrQSuQyWGz2cLRXpX2GCVhS1Z6zIkbMeIF9bBlqWn2eq5bmcWBTVIJkPXZ0mWBCQX2wiEBz+mcwRxiOb7HWeW9bkvGNeH1Mx0YJIRSw8utVLDdg/DgCLb+ncbSo7hbJ0GHFnhdO6vk/rzCjc9tYIbCxYpF2A6RRoEJlCbowgA/9LzXfrO/OeIvKhHRGbeWW2nHSZneCc3y5U79cn3KRqSH8BdNKrSjS2KAm1jaFkgsgGMnWJanWwYUtTVwr30VR8e8Vww5zwbVSjFKLvKXK65iIP7pK6Dyy47U0n7t/23NyNysJOrfMbkMb5LTfzQa7mnnJrar1R7EFo2wnBjulSEmc0kiO5QgCp6OM4Dt/b/9vlgebOkMlnauH/2o8cB5I7ItvWlsl1ZhovMR3Px7lSC6KTgvX96YJ0RcB/DN3AWCzpvJoKPZn0cJikhIOWoq+OCf8qrfg4hr5OoIfgoTkhDOMzLsrCOE7V4I1Y="}]}';
    }
}
