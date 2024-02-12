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

namespace Fabiang\LocalizeHelper;

use Laminas\Filter\FilterInterface;
use Laminas\Mvc\I18n\Translator;
use Laminas\ServiceManager\Initializer\InitializerInterface;
use Laminas\Validator\ValidatorInterface;
use Laminas\View\Helper\HelperInterface;
use Psr\Container\ContainerInterface;

use function method_exists;

class LocaleInitializer implements InitializerInterface
{
    protected ?string $locale = null;

    /** @var string[] */
    protected array $supportedInterfaces = [
        ValidatorInterface::class,
        FilterInterface::class,
        HelperInterface::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $instance): void
    {
        foreach ($this->supportedInterfaces as $interface) {
            if ($instance instanceof $interface) {
                if (method_exists($instance, 'setLocale')) {
                    if (null !== ($locale = $this->getLocale($container))) {
                        $instance->setLocale($locale);
                    }
                }
                break;
            }
        }
    }

    private function getLocale(ContainerInterface $container): ?string
    {
        if (! $container->has('MvcTranslator')) {
            return null;
        }

        /** @var Translator $translator */
        $translator = $container->get('MvcTranslator')->getTranslator();

        if (method_exists($translator, 'getLocale')) {
            $this->locale = $translator->getLocale();
        }

        return $this->locale;
    }
}
