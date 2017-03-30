# SilexBase

Biblioteca de Serviço REST do OnyxERP


# Installation

This extension requires:

* PHP 5.6+
* guzzlehttp/guzzle  6.2.3  Guzzle is a PHP HTTP client library
* monolog/monolog    1.22.1 Sends your logs to files, sockets, inboxes, databases and various web services
* silex/silex        v2.0.4 The PHP micro-framework based on the Symfony Components
* symfony/config     v3.2.6 Symfony Config Component
* symfony/validator  v3.2.6 Symfony Validator Component

## Through Composer

The easiest way to keep your suite updated is to use `Composer <http://getcomposer.org>`_:

```
wget -nc https://getcomposer.org/composer.phar
php composer.phar install
```

1. Define dependencies in your ``composer.json``:

```

        {
            "require": {
                ...

                "onyxerp/silexbase": "*"
            },
            ...
            "repositories": [
                {
                    "type": "git",
                    "url": "https://github.com/BRAConsultoria/SilexBase"
                }
            ],
            "config": {
                "bin-dir": "bin/"
            }
        }
```

2. Install/update your vendors:

```
        $ composer update onyxerp/silexbase
```


## Usage

```

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/hello/{name}', function($name) use($app) {
    return 'Hello '.$app->escape($name);
});

$app->run();
```

