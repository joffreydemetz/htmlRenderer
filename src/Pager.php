<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Renderer;

use JDZ\Renderer\PagerPage;
use JDZ\Renderer\Renderable;

/**
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
class Pager extends Renderable
{
  protected string $renderer = 'pager';

  private int $maxPageNum;
  private ?int $currentPageNum = null;
  private ?string $baseUrl = null;
  private string $prevSymbol = '«';
  private string $nextSymbol = '»';
  private string $prevText = 'Previous';
  private string $nextText = 'Next';
  private array $pages = [];

  public function __construct(int $maxPageNum = 10)
  {
    $this->maxPageNum = $maxPageNum;
  }

  public function load(int $nbPages, int $page, string $baseUrl = '')
  {
    $this->currentPageNum = $page;
    $this->baseUrl = $baseUrl;

    for ($i = 0; $i < $nbPages; $i++) {
      $pagerPage = $this->addPage($i + 1);

      if ($this->currentPageNum === $pagerPage->getPage()) {
        $pagerPage->withActive();
      }
    }

    if ($nbPages > $this->maxPageNum + 1) {
      /*
       * 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28
       * .................... ** ** ** ** ** __ ** ** ** ** ** ....................
       * currentPage = 16;
       * maxPageNum  = 16;
       * startI = currentPage - (maxPageNum/2) = 11;
       * endI   = currentPage + (maxPageNum/2) = 21;
       */
      $startI = $this->currentPageNum - ($this->maxPageNum / 2);
      $endI = $this->currentPageNum + ($this->maxPageNum / 2);
      if ($startI < 0) {
        $endI = $endI + abs($startI);
        $startI = 0;
      }

      foreach ($this->pages as $pagerPage) {
        $show = true;
        if ($pagerPage->getPage() < $startI) {
          $show = false;
        } elseif ($pagerPage->getPage() > $endI) {
          $show = false;
        }

        if (false === $show) {
          $pagerPage
            ->withDisabled()
            ->withHidden()
            ->setText('...');

          if (1 === $pagerPage->getPage() || $nbPages === $pagerPage->getPage()) {
            // show first & last page holding the dots
            $pagerPage->withHidden(false);
          }
        }
      }
    }

    return $this;
  }

  public function getPage(int $page): PagerPage
  {
    if (!isset($this->pages[$page])) {
      throw new \RuntimeException('Page ' . $page . ' not found');
    }

    return $this->pages[$page];
  }

  public function addPage(int $page): PagerPage
  {
    if (!isset($this->pages[$page])) {
      $this->pages[$page] = new PagerPage($page);
    }

    return $this->getPage($page);
  }

  public function removePage(int $page)
  {
    unset($this->pages[$page]);
    return $this;
  }

  public function toData(): array
  {
    $data = parent::toData();

    $data['pages'] = $this->renderPages();

    return $data;
  }

  protected function renderPages(): array
  {
    $pages = [];

    if ($this->currentPageNum < 1) {
      $this->currentPageNum = 1;
    }

    if (count($this->pages) > 1) {
      $current = $this->getPage($this->currentPageNum);

      ksort($this->pages);
      $_pages = array_values($this->pages);

      $previous = clone $current;
      $previous->withActive(false);
      $previous->setPage($this->currentPageNum - 1);
      $previous->setText($this->prevSymbol);
      $previous->addStyle('prev');
      if (1 === $current->getPage()) {
        $previous->withDisabled();
        $previous->setTip('');
      } else {
        $previous->setTip($this->prevText);
      }
      array_unshift($_pages, $previous);

      $next = clone $current;
      $next->withActive(false);
      $next->setPage($this->currentPageNum + 1);
      $next->setText($this->nextSymbol);
      $next->addStyle('next');
      if ($this->currentPageNum === count($this->pages)) {
        $next->withDisabled();
        $next->setTip('');
      } else {
        $next->setTip($this->nextText);
      }
      $_pages[] = $next;

      foreach ($_pages as $page) {
        if ($this->baseUrl) {
          $page->setHref($this->baseUrl . (false !== strpos($this->baseUrl, '?') ? '&' : '?') . 'page=' . $page->getPage());
        } else {
          $page->addDataAttr('page', $page->getPage());
        }
        $pages[] = $page->toData();
      }
    }

    return $pages;
  }
}
