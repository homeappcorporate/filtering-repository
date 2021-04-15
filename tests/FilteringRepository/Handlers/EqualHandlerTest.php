<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\Equal;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\FilteringRepository\Handlers\EqualHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EqualHandlerTest extends TestCase
{
    public function testIsFinalTrue(): void
    {
        $equalHandler = new EqualHandler();
        self::assertTrue($equalHandler->isFinal());
    }

    public function testIsSupportedTrue(): void
    {
        $equalHandler = new EqualHandler();

        /** @var MockObject|Equal */
        $stub = $this->createStub(Equal::class);
        self::assertTrue($equalHandler->isSupported($stub));
    }

    public function testIsSupportedFalse(): void
    {
        $equalHandler = new EqualHandler();

        /** @var FilterField|MockObject */
        $stub = $this->getMockForAbstractClass(FilterField::class, ['test']);
        self::assertFalse($equalHandler->isSupported($stub));
    }

    public function testAddFiled(): void
    {
        $equalHandler = new EqualHandler();

        /** @var QueryBuilder|MockObject */
        $qbMock = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $fieldName = 'test';
        $fieldValue = 'hell';

        $qbMock->expects(self::once())
            ->method('andWhere')
            ->with("a.{$fieldName} = :a{$fieldName}Eq")
            ->willReturnSelf();

        $qbMock->expects(self::once())
            ->method('setParameter')
            ->with("a{$fieldName}Eq", $fieldValue);

        /** @var Equal|MockObject */
        $equalMock = $this->createPartialMock(
            Equal::class,
            ['getName', 'getValue']
        );

        $equalMock->expects(self::once())
            ->method('getValue')
            ->willReturn($fieldValue);

        $equalMock
            ->expects(self::exactly(3))
            ->method('getName')
            ->willReturn($fieldName);

        $equalHandler->addFilter($equalMock, $qbMock);
    }
}

