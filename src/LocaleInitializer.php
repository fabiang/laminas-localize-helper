<?php

/**
 * Copyright 2016 Fabian Grutschus. All rights reserved.
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
 * @author    Fabian Grutschus <f.grutschus@lubyte.de>
 * @copyright 2016 Fabian Grutschus. All rights reserved.
 * @license   BSD-2-Clause
 * @link      https://github.com/fabiang/zf-localize-helper
 */

namespace Fabiang\LocalizeHelper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\ValidatorInterface;
use Zend\Filter\FilterInterface;
use Zend\View\Helper\HelperInterface;

class LocaleInitializer implements InitializerInterface
{
    /**
     * @var string
     */
    protected $locale = null;

    /**
     * @var string[]
     */
    protected $supportedInterfaces = [
        ValidatorInterface::class,
        FilterInterface::class,
        HelperInterface::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $instance)
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

    /**
     * {@inheritDoc}
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if (method_exists($serviceLocator, 'getServiceLocator')) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $this($serviceLocator, $instance);
    }

    /**
     * @param ContainerInterface $container
     * @return string
     */
    private function getLocale(ContainerInterface $container)
    {
        if (!$container->has('MvcTranslator')) {
            return null;
        }

        /* @var $translator \Zend\Mvc\I18n\Translator */
        $translator = $container->get('MvcTranslator')->getTranslator();

        if (method_exists($translator, 'getLocale')) {
            $this->locale = $translator->getLocale();
        }

        return $this->locale;
    }
}
