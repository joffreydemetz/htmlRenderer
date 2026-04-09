<?php

namespace JDZ\Renderer\Tests;

use PHPUnit\Framework\TestCase;
use JDZ\Renderer\Button;

class ElementTest extends TestCase
{
    public function testNameGetterSetter(): void
    {
        $btn = new Button('original');
        $result = $btn->setName('changed');

        $this->assertEquals('changed', $btn->getName());
        $this->assertSame($btn, $result);
    }

    public function testPositionGetterSetter(): void
    {
        $btn = new Button('btn');
        $result = $btn->setPosition(5);

        $this->assertEquals(5, $btn->getPosition());
        $this->assertSame($btn, $result);
    }

    public function testPositionDefaultsToZero(): void
    {
        $btn = new Button('btn');

        $this->assertEquals(0, $btn->getPosition());
    }

    public function testCloneResetsPosition(): void
    {
        $btn = new Button('btn');
        $btn->setPosition(10);

        $clone = clone $btn;

        $this->assertEquals(0, $clone->getPosition());
        $this->assertEquals(10, $btn->getPosition());
    }

    public function testToDataIncludesPositionWhenSet(): void
    {
        $btn = new Button('btn');
        $btn->setText('text');
        $btn->setPosition(3);

        $data = $btn->toData();

        $this->assertEquals(3, $data['position']);
    }

    public function testToDataOmitsPositionWhenZero(): void
    {
        $btn = new Button('btn');
        $btn->setText('text');

        $data = $btn->toData();

        $this->assertArrayNotHasKey('position', $data);
    }
}
