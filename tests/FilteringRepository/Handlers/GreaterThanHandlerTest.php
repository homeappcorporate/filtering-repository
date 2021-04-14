<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\QueryBuilder;
use Homeapp\FilteringRepository\Handlers\GreaterThanHandler;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\GreaterThan;
use PHPUnit\Framework\MockObject\MockObject;

class GreaterThanHandlerTest extends TestCase
{
    public function testIsFinalTrue(): void
    {
        $greaterThanHandler = new GreaterThanHandler();
        self::assertTrue($greaterThanHandler->isFinal());
    }

    public function testIsSupportedTrue(): void
    {
        $greaterThanHandler = new GreaterThanHandler();

        /** @var MockObject|GreaterThan */
        $stub = $this->createStub(GreaterThan::class);
        self::assertTrue($greaterThanHandler->isSupported($stub));
    }

    public function testIsSupportedFalse(): void
    {
        $greaterThanHandler = new GreaterThanHandler();

        /** @var FilterField|MockObject */
        $stub = $this->getMockForAbstractClass(FilterField::class, ['test']);
        self::assertFalse($greaterThanHandler->isSupported($stub));
    }

    public function testAddFiled(): void
    {
        $greaterThanHandler = new GreaterThanHandler();

        /** @var QueryBuilder|MockObject */
        $qbMock = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $fieldName = 'test';
        $fieldValue = 'hell';

        $qbMock->expects(self::once())
            ->method('andWhere')
            ->with("a.{$fieldName} > :a{$fieldName}Gt")
            ->willReturnSelf();

        $qbMock->expects(self::once())
            ->method('setParameter')
            ->with("a{$fieldName}Gt", $fieldValue)
            ->willReturnSelf();

        /** @var GreaterThan|MockObject */
        $greaterThanHandlerMock = $this->createPartialMock(
            GreaterThan::class,
            ['getName', 'getValue']
        );

        $greaterThanHandlerMock->expects(self::exactly(3))
            ->method('getName')
            ->willReturn($fieldName);

        $greaterThanHandlerMock->expects(self::once())
            ->method('getValue')
            ->willReturn($fieldValue);

        $greaterThanHandler->addFilter($greaterThanHandlerMock, $qbMock);
    }
}
