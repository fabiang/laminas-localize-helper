<?php

/**
 * Copyright 2016-2022 Fabian Grutschus. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are those
 * of the authors and should not be interpreted as representing official policies,
 * either expressed or implied, of the copyright holders.
 *
 * @link      https://github.com/fabiang/laminas-localize-helper
 */

declare(strict_types=1);

namespace Fabiang\LocalizeHelper\Behat;

use Behat\Behat\Context\Context;
use Fabiang\LocalizeHelper\LocaleInitializer;
use Laminas\Mvc\Application;
use Laminas\Mvc\I18n\Module;
use PHPUnit\Framework\Assert;

use function class_exists;
use function date_default_timezone_set;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private string $locale;
    private object $plugin;

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
    public function localeIs(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @When I request plugin :name from :pluginManager
     */
    public function iRequestPluginFrom(string $name, string $pluginManager): void
    {
        $this->plugin = $this->getApplication()
            ->getServiceManager()
            ->get($pluginManager)
            ->get($name);
    }

    /**
     * @Then Locale of plugin should be :locale
     */
    public function localeOfPluginShouldBe(string $locale): void
    {
        Assert::assertSame($locale, $this->plugin->getLocale());
    }

    public function getApplication(): Application
    {
        $modules = [];

        // ZF3
        if (class_exists(Module::class)) {
            $modules = [
                'Laminas\I18n',
                'Laminas\Mvc\I18n',
                'Laminas\Validator',
                'Laminas\Filter',
                'Laminas\Router',
            ];
        }

        return Application::init([
            'modules'                 => $modules,
            'module_listener_options' => [
                'extra_config' => [
                    'translator'   => [
                        'locale' => $this->locale,
                    ],
                    'validators'   => [
                        'initializers' => [
                            LocaleInitializer::class,
                        ],
                    ],
                    'filters'      => [
                        'initializers' => [
                            LocaleInitializer::class,
                        ],
                    ],
                    'view_helpers' => [
                        'initializers' => [
                            LocaleInitializer::class,
                        ],
                    ],
                ],
            ],
        ]);
    }
}
