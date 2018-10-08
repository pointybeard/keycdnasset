# KeyCDN Asset

- Version: v1.0.0
- Date: Oct 08 2018
- [Release notes](https://github.com/pointybeard/keycdnasset/blob/master/CHANGELOG.md)
- [GitHub repository](https://github.com/pointybeard/keycdnasset)

[![Latest Stable Version](https://poser.pugx.org/pointybeard/keycdnasset/version)](https://packagist.org/packages/pointybeard/keycdnasset) [![License](https://poser.pugx.org/pointybeard/keycdnasset/license)](https://packagist.org/packages/pointybeard/keycdnasset)

Library for checking availability of assets and pushing new assets to [KeyCDN](https://www.keycdn.com/).

## Installation

KeyCDN Asset is installed via [Composer](http://getcomposer.org/). To install, use `composer require pointybeard/keycdnasset` or add `"pointybeard/keycdnasset": "~1.0"` to your `composer.json` file.

# Usage Example

Here is a quick example of how to use this group of classes

```<?php

include "vendor/autoload.php";
use pointybeard\KeyCDNAsset\Lib;

$zone = new Lib\Zone(
    "myzone",
    "https://myzone-90f.kxcdn.com",
    Lib\Zone::FLAG_SECURE
);

$credentials = new Lib\Credentials(
    "my-username",
    "my-secure-token-key"
)

$asset = new Lib\Asset(
    $credentials,
    "/path/to/asset/on/this/zone/file.zip",
    $zone
)

### CHECKING AVAILABILITY ###
var_dump($asset->available());

### GENERATING A LINK TO THE ASSET ###
print (string)$asset->link();

### PUSHING AN ASSET ###

try{
    $result = NULL;

    //This ensures when the file is pushed, it doesn't retain all the source path info.
    chdir('/some/local/assets');

    $asset = Lib\Asset::push(
        $credentials,
        "file.zip",
        $zone,
        NULL, //Specify a different KeyCDN Host value here
        $result
    );
    var_dump($asset, $asset->available());

} catch (Lib\Exceptions\KeyCDNFailedToSyncException $ex) {
    print "Looks like pushing to CDN has failed - {$ex->getMessage()}";
    exit;

} finally {
    var_dump($result);
}

```

## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pointybeard/keycdnasset/issues),
or better yet, fork the library and submit a pull request.

## Contributing

We encourage you to contribute to this project. Please check out the [Contributing documentation](https://github.com/pointybeard/keycdnasset/blob/master/CONTRIBUTING.md) for guidelines about how to get involved.

## License

"KeyCDN Asset" is released under the [MIT License](http://www.opensource.org/licenses/MIT).
