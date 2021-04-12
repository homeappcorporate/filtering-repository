<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use Homeapp\Filter\DTO\Field\IsNotNull;
use PHPUnit\Framework\TestCase;

class IsNotNullTest extends TestCase
{
    public function testGetName()
    {
        $fieldName = 'test';
        $isNotNull = new IsNotNull($fieldName);

        self::assertEquals($fieldName, $isNotNull->getName());
    }

    public function testIsEmpty()
    {
        $isNotNull = new IsNotNull('test');

        self::assertFalse($isNotNull->isEmpty());
    }

    public function testNullValue()
    {
        $isNotNull = new IsNotNull('test');

        self::assertNull($isNotNull->getValue());
    }
}
