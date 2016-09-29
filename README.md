# ZF Localize helper

Initializer that passes your configured locale to all view helpers, validators
and filters, so you don't have to do this every time.

This module works with Zend Framework 2 and 3, but
`zendframework/zend-servicemanager` must be at least at version 2.7.6.

[![Build Status](https://travis-ci.org/fabiang/zf-localize-helper.svg?branch=master)](https://travis-ci.org/fabiang/zf-localize-helper)

## Requirements

Please see the [composer.json](composer.json) file.

## Installation

Run the following `composer` command:

```console
$ composer require fabiang/zf-localize-helper
```

## Configuration

Put the following into your plugin managers config (e.g. `config/autoload/i18n.global.php`):

```php
<?php

return [
    'translator' => [
        'locale' => 'en_UE', // this locale will be passed
    ],
    'validators' => [
        'initializers' => [
            \Fabiang\LocalizeHelper\LocaleInitializer::class,
        ]
    ],
    'filters' => [
        'initializers' => [
            \Fabiang\LocalizeHelper\LocaleInitializer::class,
        ]
    ],
    'view_helpers' => [
        'initializers' => [
            \Fabiang\LocalizeHelper\LocaleInitializer::class,
        ]
    ]
];

```

You can configure the helper for each type of plugin by removing or adding
it to the ValidatorManager/FilterManager/ViewHelperManager config above.


## LICENSE

BSD-2-Clause. See the [LICENSE](LICENSE.md).
