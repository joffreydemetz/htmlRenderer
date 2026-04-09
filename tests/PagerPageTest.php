<?php

namespace JDZ\Renderer\Tests;

use PHPUnit\Framework\TestCase;
use JDZ\Renderer\PagerPage;
use JDZ\Renderer\Button;

class PagerPageTest extends TestCase
{
    public function testExtendsButton(): void
    {
        $page = new PagerPage('5');

        $this->assertInstanceOf(Button::class, $page);
    }

    public function testConstructorSetsPageAndText(): void
    {
        $page = new PagerPage('3');

        $this->assertEquals(3, $page->getPage());
    }

    public function testSetPage(): void
    {
        $page = new PagerPage('1');
        $result = $page->setPage(10);

        $this->assertEquals(10, $page->getPage());
        $this->assertSame($page, $result);
    }

    public function testWithActiveAddsStyle(): void
    {
        $page = new PagerPage('1');
        $result = $page->withActive();

        $data = $page->toData();
        $this->assertStringContainsString('active', $data['attrs']['class']);
        $this->assertSame($page, $result);
    }

    public function testWithDisabledAddsStyle(): void
    {
        $page = new PagerPage('1');
        $page->withDisabled();

        $data = $page->toData();
        $this->assertStringContainsString('disabled', $data['attrs']['class']);
    }

    public function testWithHiddenAddsStyle(): void
    {
        $page = new PagerPage('1');
        $page->withHidden();

        $data = $page->toData();
        $this->assertStringContainsString('hidden', $data['attrs']['class']);
    }

    public function testActiveTakesPrecedenceOverHiddenAndDisabled(): void
    {
        $page = new PagerPage('1');
        $page->withActive();
        $page->withHidden();
        $page->withDisabled();

        $data = $page->toData();
        $this->assertStringContainsString('active', $data['attrs']['class']);
        $this->assertStringNotContainsString('hidden', $data['attrs']['class']);
        $this->assertStringNotContainsString('disabled', $data['attrs']['class']);
    }

    public function testNoHrefAddsDataPageAttr(): void
    {
        $page = new PagerPage('5');

        $data = $page->toData();

        $this->assertArrayHasKey('data-page', $data['attrs']);
        $this->assertEquals(5, $data['attrs']['data-page']);
    }

    public function testWithActiveCanBeToggled(): void
    {
        $page = new PagerPage('1');
        $page->withActive(true);
        $page->withActive(false);

        $data = $page->toData();
        // Not active, so no active class - it should fall through to other conditions
        $this->assertStringNotContainsString('active', $data['attrs']['class'] ?? '');
    }

    public function testFluentSetters(): void
    {
        $page = new PagerPage('1');

        $this->assertSame($page, $page->withActive());
        $this->assertSame($page, $page->withDisabled());
        $this->assertSame($page, $page->withHidden());
    }
}
