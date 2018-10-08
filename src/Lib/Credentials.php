<?php

namespace pointybeard\KeyCDNAsset\Lib;

use pointybeard\PropertyBag\Lib\PropertyBag;
use pointybeard\PropertyBag\Lib\ImmutableProperty;

final class Credentials extends PropertyBag
{
    public function __construct($token, $username)
    {
        $this->token = new ImmutableProperty("token", $token);
        $this->username = new ImmutableProperty("username", $username);
    }
}
