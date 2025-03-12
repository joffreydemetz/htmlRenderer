<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Renderer;

use JDZ\Renderer\Element;

/**
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
trait ElementsTrait
{
  protected array $elements = [];
  protected int $currentPosition = 0;

  public function getElement(string $name): Element
  {
    if (false === $this->hasElement($name)) {
      throw new \Exception('Element ' . $name . ' not found');
    }
    return $this->elements[$name];
  }

  public function hasElement(string $name): bool
  {
    return isset($this->elements[$name]);
  }

  public function addElement(Element $element): mixed
  {
    $name = $element->getName();

    if (true === $this->hasElement($name)) {
      throw new \Exception('Element ' . $name . ' already exists');
    }

    $element->setPosition(++$this->currentPosition);

    $this->elements[$name] = $element;

    return $this->elements[$name];
  }

  public function setElementPosition(string $positionElementName, int|string $position, string $direction = 'before')
  {
    $positionKeys = [];
    foreach ($this->elements as $element) {
      $positionKeys[$element->position] = $element->getName();
    }
    ksort($positionKeys);

    if ('first' === $position) {
      $position = 1;
      $direction = 'before';
    } elseif ('last' === $position) {
      $position = count($this->elements);
      $direction = 'after';
    } else {
      $position = (int)$position;
    }

    $positionElement = $this->getElement($positionElementName);

    $this->currentPosition = 0;

    foreach ($positionKeys as $name) {
      $element = $this->getElement($name);

      if ($name === $positionElement->getName()) {
        // ignore Element being positionned
        continue;
      }

      // position Element before
      if ('before' === $direction && $this->currentPosition + 1 === $position) {
        $positionElement->setPosition(++$this->currentPosition);
      }

      $element->setPosition(++$this->currentPosition);

      // position Element after
      if ('after' === $direction && $this->currentPosition + 1 === $position) {
        $positionElement->setPosition(++$this->currentPosition);
      }
    }

    return $this;
  }

  public function setElementPositionAfter(string $positionElementName, string $offsetElementName)
  {
    return $this->setElementPosition($positionElementName, $this->getElement($offsetElementName)->getPosition(), 'after');
  }

  public function setElementPositionBefore(string $positionElementName, string $offsetElementName)
  {
    return $this->setElementPosition($positionElementName, $this->getElement($offsetElementName)->getPosition(), 'before');
  }

  protected function renderElements(): array
  {
    $elements = [];
    $elementsNotPositionned = [];

    foreach ($this->elements as $element) {
      if (0 === $element->position) {
        $elementsNotPositionned[] = $element->toData();
        continue;
      }
      $elements[$element->position] = $element->toData();
    }

    ksort($elements);

    return array_merge(array_values($elements), array_values($elementsNotPositionned));
  }
}
