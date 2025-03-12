<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Renderer;

use JDZ\Renderer\RenderableInterface;

/**
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
abstract class Renderable implements RenderableInterface
{
  protected string $renderer;
  public int $position = 0;
  public array $dataAttrs = [];
  public array $ariaAttrs = [];
  public array $styles = [];

  public function __clone()
  {
    $this->position = 0;
  }

  public function setRenderer(string $renderer)
  {
    $this->renderer = $renderer;
    return $this;
  }

  public function setPosition(int $position)
  {
    $this->position = $position;
    return $this;
  }

  public function getDataAttr(string $key): mixed
  {
    return $this->dataAttrs[$key] ?? null;
  }

  public function addDataAttr(string $key, mixed $value)
  {
    $this->dataAttrs[$key] = $value;
    return $this;
  }

  public function removeDataAttr(string $key)
  {
    unset($this->dataAttrs[$key]);
    return $this;
  }

  public function getAriaAttr(string $key): mixed
  {
    return $this->ariaAttrs[$key] ?? null;
  }

  public function addAriaAttr(string $key, mixed $value)
  {
    $this->ariaAttrs[$key] = $value;
    return $this;
  }

  public function removeAriaAttr(string $key)
  {
    unset($this->ariaAttrs[$key]);
    return $this;
  }

  public function addStyles(array|string $styles)
  {
    if (!is_array($styles)) {
      $styles = explode(' ', $styles);
    }
    foreach ($styles as $style) {
      $this->addStyle($style);
    }
    return $this;
  }

  public function addStyle(string $style)
  {
    if (!in_array($style, $this->styles)) {
      $this->styles[] = $style;
    }
    return $this;
  }

  public function removeStyle(string $style)
  {
    if (false !== ($k = array_search($style, $this->styles))) {
      unset($this->styles[$k]);
    }
    return $this;
  }

  public function toData(): array
  {
    $data = [];

    $data['renderer'] = $this->renderer;

    if ($this->position) {
      $data['position'] = $this->position;
    }

    $data['attrs'] = $this->renderAttrs();

    return $data;
  }

  protected function renderAttrs(): array
  {
    $attrs = [];

    if ($this->styles) {
      $this->styles = array_unique($this->styles);
      $attrs['class'] = implode(' ', $this->styles);
    }

    if ($this->dataAttrs) {
      foreach ($this->dataAttrs as $key => $value) {
        $attrs['data-' . $key] = $value;
      }
    }

    if ($this->ariaAttrs) {
      foreach ($this->ariaAttrs as $key => $value) {
        $attrs['aria-' . $key] = $value;
      }
    }

    return $attrs;
  }
}
