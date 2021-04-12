<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use Homeapp\Filter\DTO\Field\IsNull;
use PHPUnit\Framework\TestCase;

class IsNullTest extends TestCase
{
    public function testGetName()
    {
        $fieldName = 'test';
        $isNull = new IsNull($fieldName);

        self::assertEquals($fieldName, $isNull->getName());
    }

    public function testIsEmpty()
    {
        $isNull = new IsNull('test');

        self::assertFalse($isNull->isEmpty());
    }

    public function testGetValue()
    {
        $isNull = new IsNull('test');

        self::assertNull($isNull->getValue());
    }
}
