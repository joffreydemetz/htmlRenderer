<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Renderer;

/**
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
trait PositionedElementTrait
{
  protected int $position = 0;

  public function setPosition(int $position)
  {
    $this->position = $position;
    return $this;
  }

  public function getPosition(): int
  {
    return $this->position;
  }
}
