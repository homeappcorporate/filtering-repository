<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\IsNotNull;
use Homeapp\Filter\DTO\Field\IsNull;
use Homeapp\FilteringRepository\Handlers\NullHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NullHandlerTest extends TestCase
{
    public function testIsFinal(): void
    {
        $handler = new NullHandler();

        self::assertTrue($handler->isFinal());
    }

    public function testIsSupportedIsNullField(): void
    {
        $handler = new NullHandler();
        /** @var MockObject|IsNull */
        $field = $this->createStub(IsNull::class);

        self::assertTrue($handler->isSupported($field));
    }

    public function testIsSupportedIsNotNullField(): void
    {
        $handler = new NullHandler();
        /** @var MockObject|IsNotNull */
        $field = $this->createStub(IsNotNull::class);

        self::assertTrue($handler->isSupported($field));
    }

    public function testIsSupportedFalse(): void
    {
        $handler = new NullHandler();

        $field = $this->getMockForAbstractClass(FilterField::class, ['test']);

        self::assertFalse($handler->isSupported($field));
    }

    public function testAddIsNullField(): void
    {
        $handler = new NullHandler();

        $fieldName = 'fieldName';

        /** @var IsNull|MockObject */
        $field = $this->createPartialMock(IsNull::class, ['getName']);

        $field->expects(self::once())
            ->method('getName')
            ->willReturn($fieldName);

        /** @var QueryBuilder|MockObject */
        $qb = $this->createPartialMock(QueryBuilder::class, ['andWhere']);

        $qb->expects(self::once())
            ->method('andWhere')
            ->with("a.{$fieldName} IS NULL");

        $handler->addFilter($field, $qb);
    }

    public function testAddIsNotNullField(): void
    {
        $handler = new NullHandler();

        $fieldName = 'fieldName';

        /** @var IsNotNull|MockObject */
        $field = $this->createPartialMock(IsNotNull::class, ['getName']);

        $field->expects(self::once())
            ->method('getName')
            ->willReturn($fieldName);

        /** @var QueryBuilder|MockObject */
        $qb = $this->createPartialMock(QueryBuilder::class, ['andWhere']);

        $qb->expects(self::once())
            ->method('andWhere')
            ->with("a.{$fieldName} IS NOT NULL");

        $handler->addFilter($field, $qb);
    }
}

