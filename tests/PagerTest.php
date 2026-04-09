<?php

namespace JDZ\Renderer\Tests;

use PHPUnit\Framework\TestCase;
use JDZ\Renderer\Pager;
use JDZ\Renderer\PagerPage;

class PagerTest extends TestCase
{
    public function testConstructorDefault(): void
    {
        $pager = new Pager();

        $data = $pager->toData();
        $this->assertEquals('pager', $data['renderer']);
        $this->assertEmpty($data['pages']);
    }

    public function testLoadCreatesPages(): void
    {
        $pager = new Pager();
        $result = $pager->load(5, 1);

        $data = $pager->toData();
        // 5 pages + prev + next = 7
        $this->assertCount(7, $data['pages']);
        $this->assertSame($pager, $result);
    }

    public function testCurrentPageIsActive(): void
    {
        $pager = new Pager();
        $pager->load(5, 3);

        $data = $pager->toData();
        // pages[0]=prev, pages[1]=page1, pages[2]=page2, pages[3]=page3(active), ...
        $activePage = $data['pages'][3]; // page 3
        $this->assertStringContainsString('active', $activePage['attrs']['class'] ?? '');
    }

    public function testPrevDisabledOnFirstPage(): void
    {
        $pager = new Pager();
        $pager->load(5, 1);

        $data = $pager->toData();
        $prev = $data['pages'][0];
        $this->assertStringContainsString('disabled', $prev['attrs']['class'] ?? '');
        $this->assertStringContainsString('prev', $prev['attrs']['class'] ?? '');
    }

    public function testNextDisabledOnLastPage(): void
    {
        $pager = new Pager();
        $pager->load(5, 5);

        $data = $pager->toData();
        $next = end($data['pages']);
        $this->assertStringContainsString('disabled', $next['attrs']['class'] ?? '');
        $this->assertStringContainsString('next', $next['attrs']['class'] ?? '');
    }

    public function testPrevHasTipWhenNotFirstPage(): void
    {
        $pager = new Pager();
        $pager->load(5, 3);

        $data = $pager->toData();
        $prev = $data['pages'][0];
        $this->assertEquals('Previous', $prev['attrs']['title']);
    }

    public function testNextHasTipWhenNotLastPage(): void
    {
        $pager = new Pager();
        $pager->load(5, 3);

        $data = $pager->toData();
        $next = end($data['pages']);
        $this->assertEquals('Next', $next['attrs']['title']);
    }

    public function testCustomSymbolsAndText(): void
    {
        $pager = new Pager();
        $pager->setPrevSymbol('<');
        $pager->setNextSymbol('>');
        $pager->setPrevText('Back');
        $pager->setNextText('Forward');
        $pager->load(3, 2);

        $data = $pager->toData();
        $prev = $data['pages'][0];
        $next = end($data['pages']);

        $this->assertEquals('<', $prev['text']);
        $this->assertEquals('>', $next['text']);
        $this->assertEquals('Back', $prev['attrs']['title']);
        $this->assertEquals('Forward', $next['attrs']['title']);
    }

    public function testBaseUrlAddsHrefToPages(): void
    {
        $pager = new Pager();
        $pager->load(3, 1, 'https://example.com/list');

        $data = $pager->toData();
        // page 1 (index 1, after prev)
        $this->assertStringContainsString('page=1', $data['pages'][1]['attrs']['href']);
        $this->assertStringContainsString('https://example.com/list?page=', $data['pages'][1]['attrs']['href']);
    }

    public function testBaseUrlWithQueryStringUsesAmpersand(): void
    {
        $pager = new Pager();
        $pager->load(3, 1, 'https://example.com/list?sort=name');

        $data = $pager->toData();
        $this->assertStringContainsString('&page=', $data['pages'][1]['attrs']['href']);
    }

    public function testNoBaseUrlAddsDataPage(): void
    {
        $pager = new Pager();
        $pager->load(3, 1);

        $data = $pager->toData();
        // page 1 should have data-page
        $this->assertArrayHasKey('data-page', $data['pages'][1]['attrs']);
    }

    public function testSinglePageProducesNoPages(): void
    {
        $pager = new Pager();
        $pager->load(1, 1);

        $data = $pager->toData();
        $this->assertEmpty($data['pages']);
    }

    public function testManyPagesHidesMiddleOnes(): void
    {
        $pager = new Pager(10);
        $pager->load(30, 15);

        $data = $pager->toData();
        // Should have pages with "..." text for hidden ones
        $hiddenPages = array_filter($data['pages'], fn($p) => $p['text'] === '...');
        $this->assertNotEmpty($hiddenPages);
    }

    public function testGetPageThrowsOnMissing(): void
    {
        $this->expectException(\RuntimeException::class);

        $pager = new Pager();
        $pager->getPage(999);
    }

    public function testAddAndRemovePage(): void
    {
        $pager = new Pager();
        $page = $pager->addPage(1);

        $this->assertInstanceOf(PagerPage::class, $page);
        $this->assertEquals(1, $page->getPage());

        $pager->removePage(1);

        $this->expectException(\RuntimeException::class);
        $pager->getPage(1);
    }

    public function testFluentSetters(): void
    {
        $pager = new Pager();

        $this->assertSame($pager, $pager->setPrevSymbol('<'));
        $this->assertSame($pager, $pager->setNextSymbol('>'));
        $this->assertSame($pager, $pager->setPrevText('Prev'));
        $this->assertSame($pager, $pager->setNextText('Next'));
    }
}
