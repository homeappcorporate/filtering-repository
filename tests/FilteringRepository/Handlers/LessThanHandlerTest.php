<?php

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\LessThan;
use Homeapp\FilteringRepository\Handlers\LessThanHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LessThanHandlerTest extends TestCase
{
    public function testIsFinalTrue(): void
    {
        $lessThanHandler = new LessThanHandler();
        self::assertTrue($lessThanHandler->isFinal());
    }

    public function testIsSupportedTrue(): void
    {
        $lessThanHandler = new LessThanHandler();

        /** @var MockObject|LessThan */
        $stub = $this->createStub(LessThan::class);
        self::assertTrue($lessThanHandler->isSupported($stub));
    }

    public function testIsSupportedFalse(): void
    {
        $lessThanHandler = new LessThanHandler();

        /** @var FilterField|MockObject */
        $stub = $this->getMockForAbstractClass(FilterField::class, ['test']);
        self::assertFalse($lessThanHandler->isSupported($stub));
    }

    public function testAddFiled(): void
    {
        $lessThanHandler = new LessThanHandler();

        /** @var QueryBuilder|MockObject */
        $qbMock = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $fieldName = 'test';
        $fieldValue = 'hell';

        $qbMock->expects(self::once())
            ->method('andWhere')
            ->with("a.{$fieldName} < :a{$fieldName}Lt")
            ->willReturnSelf();

        $qbMock->expects(self::once())
            ->method('setParameter')
            ->with("a{$fieldName}Lt", $fieldValue)
            ->willReturnSelf();

        /** @var LessThan|MockObject */
        $lessThanHandlerMock = $this->createPartialMock(
            LessThan::class,
            ['getName', 'getValue']
        );

        $lessThanHandlerMock->expects(self::exactly(3))
            ->method('getName')
            ->willReturn($fieldName);

        $lessThanHandlerMock->expects(self::once())
            ->method('getValue')
            ->willReturn($fieldValue);

        $lessThanHandler->addFilter($lessThanHandlerMock, $qbMock);
    }
}

