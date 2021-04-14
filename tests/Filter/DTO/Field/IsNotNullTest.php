<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use Homeapp\Filter\DTO\Field\IsNotNull;
use PHPUnit\Framework\TestCase;

class IsNotNullTest extends TestCase
{
    public function testGetName(): void
    {
        $fieldName = 'test';
        $isNotNull = new IsNotNull($fieldName);

        self::assertEquals($fieldName, $isNotNull->getName());
    }

    public function testIsEmpty(): void
    {
        $isNotNull = new IsNotNull('test');

        self::assertFalse($isNotNull->isEmpty());
    }

    public function testNullValue(): void
    {
        $isNotNull = new IsNotNull('test');

        self::assertNull($isNotNull->getValue());
    }
}
