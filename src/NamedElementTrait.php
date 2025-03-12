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
trait NamedElementTrait
{
  protected string $name = '';

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name)
  {
    $this->name = $name;
    return $this;
  }
}
