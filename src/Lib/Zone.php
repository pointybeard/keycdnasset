<?php

namespace pointybeard\KeyCDNAsset\Lib;

use pointybeard\PropertyBag\Lib\PropertyBag;
use pointybeard\PropertyBag\Lib\ImmutableProperty;

final class Zone extends PropertyBag
{
    const FLAG_SECURE = 0x0001;

    public function __construct($name, $url, $flags=null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->flags = $flags;
    }

    /**
     * Convienence method for determining if a FLAG_* constant is set
     *
     * @return boolean true if the flag is set
     */
    protected static function isFlagSet($flags, $flag)
    {
        // Flags support bitwise operators so it's easy to see
        // if one has been set.
        return ($flags & $flag) == $flag;
    }

    public function isSecure()
    {
        return self::isFlagSet($this->flags, self::FLAG_SECURE);
    }
}
