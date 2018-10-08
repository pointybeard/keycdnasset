<?php
namespace pointybeard\KeyCDNAsset\Lib;

use Symfony\Component\HttpFoundation\Response;
use pointybeard\URIInfo\Lib\URIInfo;

/**
 * Object for generating secure KeyCDN download links.
 */
class Asset
{
    const DEFAULT_HOST = "rsync.keycdn.com";

    protected $credentials;
    protected $zone;
    protected $path;

    /**
     * Constructor for the class.
     *
     * @param Credentials $credentials
     * @param Zone $zone
     * @param string $host
     */
    public function __construct(Credentials $credentials, $path, Zone $zone)
    {
        $this->credentials = $credentials;
        $this->zone = $zone;
        $this->path = $path;
    }

    public function credentials()
    {
        return $this->credentials;
    }

    public function zone()
    {
        return $this->zone;
    }

    public function path()
    {
        return $this->path;
    }

    public function available(&$info=null)
    {
        $info = $this->link()->status();

        return (
            ($info instanceof URIInfo) &&
            (int)$info->http_code == Response::HTTP_OK
        );
    }

    public function link($expiry=SecureLink::DEFAULT_LINKEXPIRYTIME)
    {
        if ($this->zone->isSecure() === true) {
            $link = new KeyCDNAssetLinkSecure(
                $this->credentials,
                $this->zone,
                $path,
                $expiry
            );
        } else {
            $link = new KeyCDNAssetLink(
                (string)$this->credentials->zone->value->url,
                $path
            );
        }

        return $link;
    }

    public static function push(Credentials $credentials, $path, Zone $zone, &$result=null, $host=self::DEFAULT_HOST)
    {
        $result = shell_exec(sprintf(
            'rsync -rtvz --progress --delete -e "ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null" --delete --chmod=D2755,F644 ./%s %s@%s:%s/ 2>&1',
            $path,
            (string)$credentials->username,
            (string)$host,
            (string)$zone->value->name
        ));

        // some basic checks to ensure things went smoothly.
        // Error will look like this:
        // rsync error: some files/attrs were not transferred (see previous errors) (code 23) at main.c(1183)
        if (preg_match("@rsync error:([^\r\n]+)@i", $result, $match)) {
            throw new Exceptions\KeyCDNFailedToSyncException(trim($match[1]));
        }

        return new self($credentials, $path, $zone);
    }
}
