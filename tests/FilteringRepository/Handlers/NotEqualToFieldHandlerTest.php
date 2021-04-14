<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\NotEqualToField;
use Homeapp\FilteringRepository\Handlers\NotEqualToFieldHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NotEqualToFieldHandlerTest extends TestCase
{
    public function testIsFinal(): void
    {
        $handler = new NotEqualToFieldHandler();
        self::assertTrue($handler->isFinal());
    }

    public function testIsSupportedTrue(): void
    {
        $handler = new NotEqualToFieldHandler();
        /** @var NotEqualToField|MockObject */
        $field = $this->createStub(NotEqualToField::class);

        self::assertTrue($handler->isSupported($field));
    }

    public function testIsSupportedFalse(): void
    {
        $handler = new NotEqualToFieldHandler();
        /** @var FilterField|MockObject */
        $field = $this->getMockForAbstractClass(FilterField::class, ['test']);

        self::assertFalse($handler->isSupported($field));
    }

    public function testAddField(): void
    {
        $handler = new NotEqualToFieldHandler();

        $fieldName = 'fieldName';
        $fieldValue = 'test';

        /** @var NotEqualToField|MockObject */
        $field = $this->createPartialMock(
            NotEqualToField::class,
            ['getName', 'getValue']
        );

        $field->expects(self::once())
            ->method('getName')
            ->willReturn($fieldName);
        $field->expects(self::once())
            ->method('getValue')
            ->willReturn($fieldValue);

        /** @var QueryBuilder|MockObject */
        $qb = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere']
        );

        $qb->expects(self::once())
            ->method('andWhere')
            ->with("a.{$fieldName} <> {$fieldValue}")
            ->willReturnSelf();

        $handler->addFilter($field, $qb);
    }
}

