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
interface RenderableInterface
{
  public function getDataAttr(string $key): mixed;

  public function addDataAttr(string $key, mixed $value);

  public function removeDataAttr(string $key);

  public function getAriaAttr(string $key): mixed;

  public function addAriaAttr(string $key, mixed $value);

  public function removeAriaAttr(string $key);

  public function addStyles(array|string $styles);

  public function addStyle(string $style);

  public function removeStyle(string $style);

  public function toData(): array;
}
