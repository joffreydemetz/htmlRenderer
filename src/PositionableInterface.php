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
interface PositionableInterface
{
  public function getPosition(): int;
  public function setPosition(int $position);
}
