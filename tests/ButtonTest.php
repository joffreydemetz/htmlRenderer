<?php

namespace JDZ\Renderer\Tests;

use PHPUnit\Framework\TestCase;
use JDZ\Renderer\Button;

class ButtonTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $btn = new Button('save');

        $this->assertEquals('save', $btn->getName());
    }

    public function testToDataBasic(): void
    {
        $btn = new Button('save');
        $btn->setText('Save');

        $data = $btn->toData();

        $this->assertEquals('button', $data['renderer']);
        $this->assertEquals('save', $data['name']);
        $this->assertEquals('a', $data['tag']);
        $this->assertEquals('Save', $data['text']);
        $this->assertEquals('#', $data['attrs']['href']);
    }

    public function testToDataWithButtonTag(): void
    {
        $btn = new Button('submit', 'button');
        $btn->setText('Submit');

        $data = $btn->toData();

        $this->assertEquals('button', $data['tag']);
        $this->assertArrayNotHasKey('href', $data['attrs']);
    }

    public function testToDataWithHrefAndTarget(): void
    {
        $btn = new Button('link', 'a', 'https://example.com', 'blank');
        $btn->setText('Visit');

        $data = $btn->toData();

        $this->assertEquals('https://example.com', $data['attrs']['href']);
        $this->assertEquals('_blank', $data['attrs']['target']);
    }

    public function testToDataWithTipAddsTooltipAttrs(): void
    {
        $btn = new Button('help');
        $btn->setText('Help');
        $btn->setTip('Click for help');

        $data = $btn->toData();

        $this->assertEquals('tooltip', $data['attrs']['data-toggle']);
        $this->assertEquals('bottom', $data['attrs']['data-placement']);
        $this->assertEquals('Click for help', $data['attrs']['title']);
    }

    public function testToDataWithIcon(): void
    {
        $btn = new Button('delete');
        $btn->setText('Delete');
        $btn->setIcon('fa fa-trash');

        $data = $btn->toData();

        $this->assertStringContainsString('<span class="fa fa-trash"></span>', $data['text']);
        $this->assertStringContainsString('Delete', $data['text']);
    }

    public function testToDataWithWidth(): void
    {
        $btn = new Button('wide');
        $btn->setText('Wide');
        $btn->setWidth(200);

        $data = $btn->toData();

        $this->assertEquals('width:200px;', $data['attrs']['style']);
    }

    public function testEmptyNameThrows(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Button name is required');

        $btn = new Button('');
        $btn->toData();
    }

    public function testInvalidTagThrows(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Button tag must be "a" or "button"');

        $btn = new Button('test');
        $btn->setTag('div');
        $btn->toData();
    }

    public function testFluentSetters(): void
    {
        $btn = new Button('btn');

        $this->assertSame($btn, $btn->setTag('button'));
        $this->assertSame($btn, $btn->setHref('https://example.com'));
        $this->assertSame($btn, $btn->setTarget('self'));
        $this->assertSame($btn, $btn->setTip('tip'));
        $this->assertSame($btn, $btn->setWidth(100));
        $this->assertSame($btn, $btn->setIcon('icon'));
        $this->assertSame($btn, $btn->setText('text'));
    }

    public function testAnchorWithHashHrefDefaultsToHash(): void
    {
        $btn = new Button('test', 'a', '#');
        $btn->setText('Test');

        $data = $btn->toData();

        $this->assertEquals('#', $data['attrs']['href']);
    }

    public function testAnchorWithNoHrefDefaultsToHash(): void
    {
        $btn = new Button('test', 'a');
        $btn->setText('Test');

        $data = $btn->toData();

        $this->assertEquals('#', $data['attrs']['href']);
    }
}
