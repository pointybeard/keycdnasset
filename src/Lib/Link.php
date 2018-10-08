<?php

namespace pointybeard\KeyCDNAsset\Lib;

use pointybeard\URIInfo\Lib\URIInfo;

class Link
{
    protected $path;
    protected $zone;

    public function __construct($path, Zone $zone)
    {
        $this->zone = $zone;
        $this->path = $path;
    }

    /**
     * Accessor method to get properties from the info array after run() method
     * has been called
     *
     * @return mixed Value of the property or false if property doesnt exist
     */
    public function __get($name)
    {
        if (!isset($this->$name)) {
            return false;
        }
        return $this->$name;
    }

    public function __toString()
    {
        //Ensure the path has a leading / otherwise KeyCDN will complain
        return (string)$this->zone->value->url . '/' . trim($this->path, '/');
    }

    public function status()
    {
        return (new URIInfo((string)$this));
    }
}
