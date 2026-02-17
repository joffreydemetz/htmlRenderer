<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Renderer\Contract;

use JDZ\Renderer\Element;

/**
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
interface ElementableInterface
{
  public function getElements(): array;
  public function getElement(string $name): Element;
  public function hasElement(string $name): bool;
  public function removeElement(string $name): static;
  public function addElement(Element $element): Element;
  public function setElementPosition(string $positionElementName, int|string $position, string $direction = 'before'): static;
  public function setElementPositionAfter(string $positionElementName, string $offsetElementName): static;
  public function setElementPositionBefore(string $positionElementName, string $offsetElementName): static;
}
