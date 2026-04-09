<?php

namespace JDZ\Renderer\Tests;

use PHPUnit\Framework\TestCase;
use JDZ\Renderer\Button;
use JDZ\Renderer\ElementsTrait;
use JDZ\Renderer\Contract\ElementableInterface;

class ElementsTraitTest extends TestCase
{
    private function createContainer(): object
    {
        return new class implements ElementableInterface {
            use ElementsTrait;
        };
    }

    public function testAddAndGetElement(): void
    {
        $container = $this->createContainer();
        $btn = new Button('save');
        $btn->setText('Save');

        $container->addElement($btn);

        $this->assertTrue($container->hasElement('save'));
        $this->assertSame($btn, $container->getElement('save'));
    }

    public function testAddElementAutoIncrementsPosition(): void
    {
        $container = $this->createContainer();

        $btn1 = new Button('first');
        $btn1->setText('First');
        $container->addElement($btn1);

        $btn2 = new Button('second');
        $btn2->setText('Second');
        $container->addElement($btn2);

        $this->assertEquals(1, $container->getElement('first')->getPosition());
        $this->assertEquals(2, $container->getElement('second')->getPosition());
    }

    public function testAddDuplicateNameThrows(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('already exists');

        $container = $this->createContainer();
        $container->addElement(new Button('btn'));
        $container->addElement(new Button('btn'));
    }

    public function testGetNonExistentElementThrows(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('not found');

        $container = $this->createContainer();
        $container->getElement('missing');
    }

    public function testRemoveElement(): void
    {
        $container = $this->createContainer();
        $container->addElement(new Button('btn'));

        $result = $container->removeElement('btn');

        $this->assertFalse($container->hasElement('btn'));
        $this->assertSame($container, $result);
    }

    public function testGetElements(): void
    {
        $container = $this->createContainer();
        $container->addElement(new Button('a'));
        $container->addElement(new Button('b'));

        $elements = $container->getElements();

        $this->assertCount(2, $elements);
        $this->assertArrayHasKey('a', $elements);
        $this->assertArrayHasKey('b', $elements);
    }

    public function testSetElementPositionFirst(): void
    {
        $container = $this->createContainer();
        $container->addElement(new Button('a'));
        $container->addElement(new Button('b'));
        $container->addElement(new Button('c'));

        $container->setElementPosition('c', 'first');

        $this->assertEquals(1, $container->getElement('c')->getPosition());
        $this->assertEquals(2, $container->getElement('a')->getPosition());
        $this->assertEquals(3, $container->getElement('b')->getPosition());
    }

    public function testSetElementPositionLast(): void
    {
        $container = $this->createContainer();
        $container->addElement(new Button('a'));
        $container->addElement(new Button('b'));
        $container->addElement(new Button('c'));

        $container->setElementPosition('a', 'last');

        $this->assertEquals(1, $container->getElement('b')->getPosition());
        $this->assertEquals(2, $container->getElement('c')->getPosition());
    }

    public function testSetElementPositionBefore(): void
    {
        $container = $this->createContainer();
        $container->addElement(new Button('a'));
        $container->addElement(new Button('b'));
        $container->addElement(new Button('c'));

        $container->setElementPositionBefore('c', 'b');

        $this->assertEquals(1, $container->getElement('a')->getPosition());
        $this->assertEquals(2, $container->getElement('c')->getPosition());
        $this->assertEquals(3, $container->getElement('b')->getPosition());
    }

    public function testSetElementPositionAfter(): void
    {
        $container = $this->createContainer();
        $container->addElement(new Button('a'));
        $container->addElement(new Button('b'));
        $container->addElement(new Button('c'));

        $container->setElementPositionAfter('a', 'b');

        $this->assertEquals(1, $container->getElement('b')->getPosition());
        $this->assertEquals(2, $container->getElement('a')->getPosition());
        $this->assertEquals(3, $container->getElement('c')->getPosition());
    }
}
