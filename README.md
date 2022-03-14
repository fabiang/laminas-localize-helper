# Laminas Localize helper

Initializer that passes your configured locale to all view helpers, validators
and filters, so you don't have to do this every time.

[![Latest Stable Version](https://poser.pugx.org/fabiang/laminas-localize-helper/version)](https://packagist.org/packages/fabiang/laminas-localize-helper)
[![License](https://poser.pugx.org/fabiang/laminas-localize-helper/license)](https://packagist.org/packages/fabiang/laminas-localize-helper)

## Installation

Run the following `composer` command:

```console
$ composer require fabiang/laminas-localize-helper
```

## Configuration

Put the following into your plugin managers config (e.g. `config/autoload/i18n.global.php`):

```php
<?php

return [
    'translator' => [
        'locale' => 'en_US', // this locale will be passed
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

You can configure the initializer for each type of plugin by removing or adding
it to the ValidatorManager/FilterManager/ViewHelperManager config above.


## LICENSE

BSD-2-Clause. See the [LICENSE](LICENSE.md).
