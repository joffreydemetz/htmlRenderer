<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Renderer\Contract;

/**
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
interface RenderableInterface
{
  public function getDataAttr(string $key): mixed;

  public function addDataAttr(string $key, mixed $value): static;

  public function removeDataAttr(string $key): static;

  public function getAriaAttr(string $key): mixed;

  public function addAriaAttr(string $key, mixed $value): static;

  public function removeAriaAttr(string $key): static;

  public function addStyles(array|string $styles): static;

  public function addStyle(string $style): static;

  public function removeStyle(string $style): static;

  public function toData(): array;
}
