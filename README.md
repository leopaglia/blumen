## Installation

* First time creating an app:
* NOT WORKING (make repo public and publish at packagist):

    `composer global require "leopaglia/blumen-installer=dev-dev-master"`

* Make sure to place the `~/.composer/vendor/bin` directory in your PATH so the lumen executable can be located by your system.

    (`export PATH=$PATH:{your/home/directory}/.composer/vendor/bin`)

-----------

ALTERNATIVE:
 
Create a composer.json file 
 
```
{
    "require": {
        "leopaglia/blumen-installer": "dev-dev-master"
    },
    "repositories": [{
        "type": "vcs",
        "url": "https://git.quadminds.com/qm/blumen-installer.git"
    }]
}
```

Run `composer install`

Run `export PATH=$PATH:vendor/bin/blumen`

-----------

## Creating a new app

`blumen new appName`

-----------

## Config

Requires override permission from apache config

```
<Directory /dir/dir2/dir3/>
	Options Indexes FollowSymLinks
	AllowOverride All
	Require all granted
</Directory>
```

