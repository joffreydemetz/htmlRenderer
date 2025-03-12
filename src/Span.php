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
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
class Span extends Renderable
{
  protected string $renderer = 'span';
  private string $text;

  public function __construct(string $text)
  {
    $this->text = $text;
  }

  public function toData(): array
  {
    $data = parent::toData();

    $data['text'] = $this->text;

    return $data;
  }
}
