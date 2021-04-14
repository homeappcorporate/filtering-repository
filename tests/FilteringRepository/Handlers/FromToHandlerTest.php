<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use PHPUnit\Framework\TestCase;
use Homeapp\FilteringRepository\Handlers\FromToHandler;
use Homeapp\Filter\DTO\Field\FromTo;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\ClassAlreadyExistsException;
use PHPUnit\Framework\MockObject\ClassIsFinalException;
use PHPUnit\Framework\MockObject\DuplicateMethodException;
use PHPUnit\Framework\MockObject\InvalidMethodNameException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use InvalidArgumentException as GlobalInvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\OriginalConstructorInvocationRequiredException;
use PHPUnit\Framework\MockObject\ReflectionException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\MockObject\UnknownTypeException;
use RuntimeException as GlobalRuntimeException;

class FromToHandlerTest extends TestCase
{
    public function testIsFinalTrue(): void
    {
        $equalHandler = new FromToHandler();
        self::assertTrue($equalHandler->isFinal());
    }

    public function testIsSupportedTrue(): void
    {
        $equalHandler = new FromToHandler();

        /** @var MockObject|FromTo */
        $stub = $this->createStub(FromTo::class);
        self::assertTrue($equalHandler->isSupported($stub));
    }

    public function testIsSupportedFalse(): void
    {
        $equalHandler = new FromToHandler();

        /** @var FilterField|MockObject */
        $stub = $this->getMockForAbstractClass(FilterField::class, ['test']);
        self::assertFalse($equalHandler->isSupported($stub));
    }

    /**
     * @group values
     */
    public function testAddFiled(): void
    {
        $equalHandler = new FromToHandler();

        /** @var QueryBuilder|MockObject */
        $qbMock = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere']
        );

        $from = null;
        $to = null;

        $qbMock->expects(self::never())->method('andWhere');

        /** @var FromTo|MockObject */
        $equalMock = $this->createPartialMock(
            FromTo::class,
            ['getFrom', 'getTo']
        );

        $equalMock->expects(self::once())->method('getFrom')->willReturn($from);
        $equalMock->expects(self::once())->method('getTo')->willReturn($to);

        $equalHandler->addFilter($equalMock, $qbMock);
    }

    public function testAddFiledWithToIsNull(): void
    {
        $equalHandler = new FromToHandler();

        /** @var QueryBuilder|MockObject */
        $qbMock = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $fieldName = 'fieldName';
        $from = 'hell';
        $to = null;

        $qbMock->expects(self::once())
            ->method('andWhere')
            ->with(self::equalTo("a.{$fieldName} >= :a{$fieldName}From"))
            ->willReturnSelf();

        $qbMock->expects(self::once())
            ->method('setParameter')
            ->with("a{$fieldName}From", $from)
            ->willReturnSelf();

        /** @var FromTo|MockObject */
        $equalMock = $this->createPartialMock(
            FromTo::class,
            ['getFrom', 'getTo', 'getName']
        );

        $equalMock->expects(self::exactly(3))->method('getName')->willReturn($fieldName);
        $equalMock->expects(self::exactly(2))->method('getFrom')->willReturn($from);
        $equalMock->expects(self::once())->method('getTo')->willReturn($to);

        $equalHandler->addFilter($equalMock, $qbMock);
    }

    public function testAddFiledWithFromIsNull(): void
    {
        $equalHandler = new FromToHandler();

        /** @var QueryBuilder|MockObject */
        $qbMock = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $fieldName = 'fieldName';
        $from = null;
        $to = 'death';

        $qbMock->expects(self::once())
            ->method('andWhere')
            ->with(self::equalTo("a.{$fieldName} <= :a{$fieldName}To"))
            ->willReturnSelf();

        $qbMock->expects(self::once())
            ->method('setParameter')
            ->with("a{$fieldName}To", $to)
            ->willReturnSelf();

        /** @var FromTo|MockObject */
        $equalMock = $this->createPartialMock(
            FromTo::class,
            ['getFrom', 'getTo', 'getName']
        );

        $equalMock->expects(self::exactly(3))->method('getName')->willReturn($fieldName);
        $equalMock->expects(self::once())->method('getFrom')->willReturn($from);
        $equalMock->expects(self::exactly(2))->method('getTo')->willReturn($to);

        $equalHandler->addFilter($equalMock, $qbMock);
    }
}

