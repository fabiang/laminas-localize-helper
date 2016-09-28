<?php

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
