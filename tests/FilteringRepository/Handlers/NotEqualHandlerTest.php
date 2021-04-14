<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\NotEqual;
use Homeapp\FilteringRepository\Handlers\NotEqualHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NotEqualHandlerTest extends TestCase
{
    public function testIsFinal(): void
    {
        $notEqualHandler = new NotEqualHandler();
        self::assertTrue($notEqualHandler->isFinal());
    }

    public function testIsSupportedTrue(): void
    {
        $notEqualHandler = new NotEqualHandler();
        /** @var NotEqual|MockObject */
        $notEqual = $this->createStub(NotEqual::class);

        self::assertTrue($notEqualHandler->isSupported($notEqual));
    }

    public function testIsSupportedFalse(): void
    {
        $notEqualHandler = new NotEqualHandler();
        /** @var FilterField|MockObject */
        $field = $this->getMockForAbstractClass(FilterField::class, ['test']);

        self::assertFalse($notEqualHandler->isSupported($field));
    }

    public function testFields(): void
    {
        $notEqualHandler = new NotEqualHandler();

        $fieldName = 'fieldName';
        $fieldValue = 'test';

        /** @var NotEqual|MockObject */
        $notEqual = $this->createPartialMock(
            NotEqual::class,
            ['getName', 'getValue']
        );
        $notEqual->expects(self::exactly(3))
            ->method('getName')
            ->willReturn($fieldName);
        $notEqual->expects(self::once())
            ->method('getValue')
            ->willReturn($fieldValue);

        /** @var QueryBuilder|MockObject */
        $qb = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $qb->expects(self::once())
            ->method('andWhere')
            ->with("a.{$fieldName} <> :a{$fieldName}Neq")
            ->willReturnSelf();

        $qb->expects(self::once())
            ->method('setParameter')
            ->with("a{$fieldName}Neq", $fieldValue)
            ->willReturnSelf();

        $notEqualHandler->addFilter($notEqual, $qb);
    }
}

