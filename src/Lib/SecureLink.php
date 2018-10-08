<?php

namespace pointybeard\KeyCDNAsset\Lib;

final class SecureLink extends Link
{
    const DEFAULT_LINKEXPIRYTIME = 1800;

    protected $credentials;
    protected $expiry;

    public function __construct(Credentials $credentials, $path, Zone $zone, $expiry=self::DEFAULT_LINKEXPIRYTIME)
    {
        $this->credentials = $credentials;
        $this->expiry = $expiry;
        parent::__construct(
            $path,
            $zone
        );
    }

    public function __toString()
    {
        try {
            //Ensure the path has a leading / otherwise KeyCDN will complain
            $path = '/' . trim($this->path, '/');
            $expiry = (time() + $this->expiry);

            // Glue the path, token and expiry together and MD5 hash the result.
            $token = md5(sprintf(
                '%s%s%d',
                $path,
                (string)$this->credentials->token,
                $expiry
            ), true);

            $token = base64_encode($token);

            // These are specific requirements of KeyCDN, mostly to ensure a valid URL.
            $token = strtr($token, '+/', '-_');
            $token = str_replace('=', '', $token);

            return sprintf(
                '%s%s?token=%s&expire=%s',
                (string)$this->zone->value->url,
                $path,
                $token,
                $expiry
            );
        } catch (\Exception $ex) {
            return "";
        }
    }
}
