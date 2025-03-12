<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Renderer;

use JDZ\Renderer\NameableInterface;
use JDZ\Renderer\PositionableInterface;
use JDZ\Renderer\Renderable;

/**
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
abstract class Element extends Renderable implements NameableInterface, PositionableInterface
{
  use \JDZ\Renderer\NamedElementTrait,
    \JDZ\Renderer\PositionedElementTrait;

  public function __clone()
  {
    $this->position = 0;
  }

  public function toData(): array
  {
    $data = parent::toData();

    if ($this->position) {
      $data['position'] = $this->position;
    }

    return $data;
  }
}
