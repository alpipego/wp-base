# WP Base
My subjective basic Composer-WordPress blueprint

## How to use 

* remove all plugins you do not wish to install
* you can add different configuration for different environments (by default `local`, `staging`, `production`)
* to get started locally, add your environment details to `config/env/local.json` (see `config/env/default.json` for a blueprint)
* add your virtual host details to `config/nginx/local.conf` and symlink that file to nginx `sites-enabled` (If you want to use apache together with php-fpm the process is pretty much the same. If your using `mod-php` you'll have to figure it out for yourself).
* if you want to use **ACF Pro** you need to add a license key (as the value for the `k` query string)
* some plugins like **WP Mail SMTP** tend to remove their old tags from the repository (if they do this the install will fail), to prevent this either change their version number to `"*"` or keep them in sync with wpackagist 

As of version 1.0.0 this can also be installed as a project:

```
composer create-project alpipego/wp-base 1.0.* --no-scripts
```

## Directory Structure

.  
├── LICENSE  
├── README.md  
├── composer.json  
├── config  
│   ├── env  
│   │   ├── default.json  
│   │   └── local.json  
│   ├── env.json  
│   └── nginx  
│       └── local.conf  
├── log  
├── web  
│   ├── assets  
│   │   └── index.php  
│   ├── extensions  
│   │   └── index.php  
│   ├── index.php  
│   ├── languages  
│   │   └── index.php  
│   ├── plugins  
│   │   └── index.php  
│   ├── uploads  
│   │   └── index.php  
│   └── wp-config.php  
└── wp-config.php  

* For a basic setup you should not have to touch more than the configuration files in `config` and the `composer.json`

## Plugins not in repository

A lot of plugins that are not in the repository can be installed by getting their zip archive:

```
{
    "type": "package",
    "package": {
        "name": "advanced-custom-fields/advanced-custom-fields-pro",
        "version": "5.3.9",
        "type": "wordpress-plugin",
        "dist": {
            "type": "zip",
            "url": "http://connect.advancedcustomfields.com/index.php?p=pro&a=download&k=LICENSE_KEY"
        }
    }
}
```

Add the correct version above and then require it with:

```
"advanced-custom-fields/advanced-custom-fields-pro": "*"
```

