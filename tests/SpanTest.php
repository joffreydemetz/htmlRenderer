<?php

namespace JDZ\Renderer\Tests;

use PHPUnit\Framework\TestCase;
use JDZ\Renderer\Span;

class SpanTest extends TestCase
{
    public function testToDataIncludesText(): void
    {
        $span = new Span('Hello World');

        $data = $span->toData();

        $this->assertEquals('span', $data['renderer']);
        $this->assertEquals('Hello World', $data['text']);
    }

    public function testToDataWithStyles(): void
    {
        $span = new Span('Styled');
        $span->addStyle('highlight');

        $data = $span->toData();

        $this->assertEquals('Styled', $data['text']);
        $this->assertEquals('highlight', $data['attrs']['class']);
    }
}
