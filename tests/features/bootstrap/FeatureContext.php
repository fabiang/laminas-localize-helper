<?php

namespace Fabiang\LocalizeHelper\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Zend\Mvc\Application;
use PHPUnit_Framework_Assert as Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @var string
     */
    private $locale;

    private $plugin;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        date_default_timezone_set('UTC');
    }

    /**
     * @Given locale is :locale
     */
    public function localeIs($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @When I request plugin :name from :pluginManager
     */
    public function iRequestPluginFrom($name, $pluginManager)
    {
        $this->plugin = $this->getApplication()
            ->getServiceManager()
            ->get($pluginManager)
            ->get($name);
    }

    /**
     * @Then Locale of plugin should be :locale
     */
    public function localeOfPluginShouldBe($locale)
    {
        Assert::assertSame($locale, $this->plugin->getLocale());
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        $modules = [];

        // ZF3
        if (class_exists('Zend\Mvc\I18n\Module')) {
            $modules = [
                'Zend\I18n',
                'Zend\Mvc\I18n',
                'Zend\Validator',
                'Zend\Filter',
                'Zend\Router',
            ];
        }

        return Application::init([
            'modules'                 => $modules,
            'module_listener_options' => [
                'extra_config' => [
                    'translator' => [
                        'locale' => $this->locale,
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
                ]
            ],
        ]);
    }
}
