<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Renderer;

use JDZ\Renderer\Renderable;

/**
 * Button 
 *
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
class Button extends Renderable
{
  use \JDZ\Renderer\NamedElementTrait;

  protected string $renderer = 'button';

  protected string $tag;
  protected ?string $href;
  protected ?string $target;

  protected string $text = '';
  protected string $tip = '';
  protected string $icon = '';
  protected int $width = 0;

  public function __construct(string $name, string $tag = 'a', ?string $href = null, ?string $target = null)
  {
    $this->name = $name;
    $this->tag = $tag;
    $this->target = $target;
    $this->href = $href;
  }

  public function setTip(string $tip)
  {
    $this->tip = $tip;
    return $this;
  }

  public function setWidth(int $width)
  {
    $this->width = $width;
    return $this;
  }

  public function setIcon(string $icon)
  {
    $this->icon = $icon;
    return $this;
  }

  public function setText(string $text)
  {
    $this->text = $text;
    return $this;
  }

  public function toData(): array
  {
    $this->validate();

    if ($this->tip) {
      $this->addDataAttr('toggle', 'tooltip');
      $this->addDataAttr('placement', 'bottom');
    }

    $data = parent::toData();

    $data['name'] = $this->name;
    $data['tag']  = $this->tag;
    $data['text'] = ('' !== $this->icon ? '<span class="' . $this->icon . '"></span> ' : '') . $this->text;

    return $data;
  }

  protected function validate(): void
  {
    if ('' === $this->name) {
      throw new \Exception('Button name is required');
    }

    if ('a' !== $this->tag && 'button' !== $this->tag) {
      throw new \Exception('Button tag must be "a" or "button"');
    }

    if (!$this->href || '#' === $this->href) {
      $this->href = null;
    }

    if ('a' === $this->tag) {
      if (!$this->href) {
        $this->href = '#';
      }
    }
  }

  protected function renderAttrs(): array
  {
    $attrs = parent::renderAttrs();

    if ($this->width > 0) {
      $attrs['style'] = 'width:' . $this->width . 'px;';
    }

    if ('a' === $this->tag) {
      $attrs['href'] = $this->href;

      if ($this->target) {
        $attrs['target'] = '_' . $this->target;
      }
    } else {
      $this->href = '';
    }

    if ('' !== $this->tip) {
      $attrs['title'] = $this->tip;
    }

    return $attrs;
  }
}
