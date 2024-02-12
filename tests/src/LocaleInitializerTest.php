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

use Laminas\I18n\Filter\Alnum as Filter;
use Laminas\I18n\Translator\Translator;
use Laminas\I18n\Validator\DateTime as Validator;
use Laminas\I18n\View\Helper\DateFormat as ViewHelper;
use Laminas\Mvc\I18n\Translator as MvcTranslator;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-28 at 13:09:06.
 *
 * @coversDefaultClass \Fabiang\LocalizeHelper\LocaleInitializer
 */
final class LocaleInitializerTest extends TestCase
{
    use ProphecyTrait;

    private LocaleInitializer $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new LocaleInitializer();
    }

    /**
     * @test
     * @covers ::__invoke
     * @covers ::getLocale
     */
    public function testInvoke(): void
    {
        $validator = $this->prophesize(Validator::class);
        $validator->setLocale('en_US')->shouldBeCalled();
        $filter = $this->prophesize(Filter::class);
        $filter->setLocale('en_US')->shouldBeCalled();
        $viewHelper = $this->prophesize(ViewHelper::class);
        $viewHelper->setLocale('en_US')->shouldBeCalled();

        $translator = $this->prophesize(Translator::class);
        $translator->getLocale()->willReturn('en_US');

        $mvcTranslator = $this->prophesize(MvcTranslator::class);
        $mvcTranslator->getTranslator()->willReturn($translator->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->has('MvcTranslator')->willReturn(true);
        $container->get('MvcTranslator')->willReturn($mvcTranslator->reveal());

        $this->object->__invoke($container->reveal(), $validator->reveal());
        $this->object->__invoke($container->reveal(), $filter->reveal());
        $this->object->__invoke($container->reveal(), $viewHelper->reveal());
    }

    /**
     * @covers ::getLocale
     */
    public function testInvokeNoMvcTranslator(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has('MvcTranslator')->willReturn(false);

        $validator = $this->prophesize(Validator::class);
        $validator->setLocale()->shouldNotBeCalled();

        $this->object->__invoke($container->reveal(), $validator->reveal());
    }
}
