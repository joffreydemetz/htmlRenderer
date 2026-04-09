<?php

namespace JDZ\Renderer\Tests;

use PHPUnit\Framework\TestCase;
use JDZ\Renderer\Span;

class RenderableTest extends TestCase
{
    public function testAddAndGetDataAttr(): void
    {
        $span = new Span('text');
        $result = $span->addDataAttr('id', 42);

        $this->assertEquals(42, $span->getDataAttr('id'));
        $this->assertSame($span, $result);
    }

    public function testGetDataAttrReturnsNullWhenMissing(): void
    {
        $span = new Span('text');

        $this->assertNull($span->getDataAttr('missing'));
    }

    public function testRemoveDataAttr(): void
    {
        $span = new Span('text');
        $span->addDataAttr('key', 'value');
        $result = $span->removeDataAttr('key');

        $this->assertNull($span->getDataAttr('key'));
        $this->assertSame($span, $result);
    }

    public function testAddAndGetAriaAttr(): void
    {
        $span = new Span('text');
        $result = $span->addAriaAttr('label', 'Close');

        $this->assertEquals('Close', $span->getAriaAttr('label'));
        $this->assertSame($span, $result);
    }

    public function testGetAriaAttrReturnsNullWhenMissing(): void
    {
        $span = new Span('text');

        $this->assertNull($span->getAriaAttr('missing'));
    }

    public function testRemoveAriaAttr(): void
    {
        $span = new Span('text');
        $span->addAriaAttr('hidden', 'true');
        $result = $span->removeAriaAttr('hidden');

        $this->assertNull($span->getAriaAttr('hidden'));
        $this->assertSame($span, $result);
    }

    public function testAddStyle(): void
    {
        $span = new Span('text');
        $result = $span->addStyle('active');

        $data = $span->toData();
        $this->assertStringContainsString('active', $data['attrs']['class']);
        $this->assertSame($span, $result);
    }

    public function testAddStyleNoDuplicates(): void
    {
        $span = new Span('text');
        $span->addStyle('btn');
        $span->addStyle('btn');

        $data = $span->toData();
        $this->assertEquals('btn', $data['attrs']['class']);
    }

    public function testAddStylesFromString(): void
    {
        $span = new Span('text');
        $result = $span->addStyles('btn primary large');

        $data = $span->toData();
        $this->assertStringContainsString('btn', $data['attrs']['class']);
        $this->assertStringContainsString('primary', $data['attrs']['class']);
        $this->assertStringContainsString('large', $data['attrs']['class']);
        $this->assertSame($span, $result);
    }

    public function testAddStylesFromArray(): void
    {
        $span = new Span('text');
        $span->addStyles(['one', 'two']);

        $data = $span->toData();
        $this->assertEquals('one two', $data['attrs']['class']);
    }

    public function testRemoveStyle(): void
    {
        $span = new Span('text');
        $span->addStyles(['keep', 'remove']);
        $result = $span->removeStyle('remove');

        $data = $span->toData();
        $this->assertStringContainsString('keep', $data['attrs']['class']);
        $this->assertStringNotContainsString('remove', $data['attrs']['class']);
        $this->assertSame($span, $result);
    }

    public function testToDataIncludesRenderer(): void
    {
        $span = new Span('text');

        $data = $span->toData();

        $this->assertEquals('span', $data['renderer']);
        $this->assertArrayHasKey('attrs', $data);
    }

    public function testRenderAttrsIncludesDataAndAria(): void
    {
        $span = new Span('text');
        $span->addDataAttr('id', '123');
        $span->addAriaAttr('label', 'Test');
        $span->addStyle('visible');

        $data = $span->toData();

        $this->assertEquals('123', $data['attrs']['data-id']);
        $this->assertEquals('Test', $data['attrs']['aria-label']);
        $this->assertEquals('visible', $data['attrs']['class']);
    }

    public function testEmptyAttrsWhenNoStylesOrAttributes(): void
    {
        $span = new Span('text');

        $data = $span->toData();

        $this->assertEmpty($data['attrs']);
    }
}
