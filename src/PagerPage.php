<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Renderer;

use JDZ\Renderer\Button;

/**
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
class PagerPage extends Button
{
  // public string $renderer = 'pager.page';
  private int $page;
  private bool $active = false;
  private bool $disabled = false;
  private bool $hidden = false;

  public function __construct(string $name, string $tag = 'a', ?string $href = null, ?string $target = null)
  {
    parent::__construct($name, $tag, $href, $target);

    $this->text = $name;
    $this->page = (int)$name;
  }

  public function getPage(): int
  {
    return $this->page;
  }

  public function withActive(bool $active = true)
  {
    $this->active = $active;
    return $this;
  }

  public function withDisabled(bool $disabled = true)
  {
    $this->disabled = $disabled;
    return $this;
  }

  public function withHidden(bool $hidden = true)
  {
    $this->hidden = $hidden;
    return $this;
  }

  public function setPage(int $page)
  {
    $this->page = $page;
    return $this;
  }

  public function toData(): array
  {
    if (true === $this->active) {
      $this->addStyle('active');
    } elseif (true === $this->hidden) {
      $this->addStyle('hidden');
    } elseif (true === $this->disabled) {
      $this->addStyle('disabled');
    }

    if (!$this->href) {
      $this->addDataAttr('page', $this->page);
    }

    $data = parent::toData();

    return $data;
  }
}
