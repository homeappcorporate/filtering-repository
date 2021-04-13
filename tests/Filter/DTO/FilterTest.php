<?php

declare(strict_types=1);

namespace Test\Filter\DTO;

use Homeapp\Filter\DTO\Field\FieldInterface;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Filter;
use Homeapp\Filter\DTO\Sorting;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\ClassAlreadyExistsException;
use PHPUnit\Framework\MockObject\ClassIsFinalException;
use PHPUnit\Framework\MockObject\DuplicateMethodException;
use PHPUnit\Framework\MockObject\InvalidMethodNameException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\OriginalConstructorInvocationRequiredException;
use PHPUnit\Framework\MockObject\ReflectionException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\MockObject\UnknownTypeException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException as RecursionContextInvalidArgumentException;

use function var_dump;

class FilterTest extends TestCase
{
    public function testSetLimits(): void
    {
        $filter = new Filter([]);

        $filter->setLimits(1, 1);
        self::assertEquals(1, $filter->getCount());
        self::assertEquals(1, $filter->getPage());
    }

    public function testRemovePaging(): void
    {
        $filter = new Filter([]);
        $filter->setLimits(1, 1);

        $filter->removePaging();
        self::assertNull($filter->getCount());
        self::assertEquals(1, $filter->getPage());
    }

    public function testWithField(): void
    {
        $filter = new Filter([]);

        /** @var MockObject|FieldInterface */
        $mock = $this->createMock(FieldInterface::class);
        $mock->expects(self::any())->method('getName')->willReturn('test');

        $filter = $filter->withField($mock);

        self::assertTrue($filter->hasField('test'));

        $field = $filter->getField('test');

        self::assertEquals($mock, $field);

        $filter = $filter->withoutField('test');

        self::assertFalse($filter->hasField('test'));

        self::assertTrue($filter->doesNotHaveField('test'));
    }

    public function testView(): void
    {
        $filter = new Filter([]);

        $viewType = 'test';

        $filter->setViewType($viewType);

        self::assertEquals($viewType, $filter->getViewType());

        $filter->setViewType(null);
        self::assertNull($filter->getViewType());
    }

    public function testGroup(): void
    {
        $filter = new Filter([]);

        $groupBy = 'id';

        $filter->setGroupBy($groupBy);

        self::assertEquals($groupBy, $filter->getGroupBy());

        $filter->setGroupBy(null);
        self::assertNull($filter->getGroupBy());
    }

    public function testGetFields(): void
    {
        $fieldName = 'test';
        /** @var MockObject|FieldInterface|FilterField */
        $mock = $this->getMockForAbstractClass(
            FilterField::class,
            [$fieldName],
            '',
            true,
            true,
            true,
            ['getName', 'isEmpty']
        );
        $mock->expects(self::any())->method('getName')->willReturn($fieldName);
        $mock->expects(self::any())->method('isEmpty')->willReturn(false);

        $filter = new Filter([$mock]);


        $fields = $filter->getFields();
        self::assertCount(1, $fields);
        self::assertEquals($mock, $fields[$fieldName]);
    }

    public function testIsEmpty(): void
    {
        $filter = new Filter(['string']);
        self::assertTrue($filter->isEmpty());
    }

    public function testGetField(): void
    {
        $fieldName = 'test';
        /** @var MockObject|FieldInterface|FilterField */
        $mock = $this->getMockForAbstractClass(
            FilterField::class,
            [$fieldName],
            '',
            true,
            true,
            true,
            ['getName', 'isEmpty']
        );
        $mock->expects(self::any())->method('getName')->willReturn($fieldName);
        $mock->expects(self::any())->method('isEmpty')->willReturn(false);

        $filter = new Filter([$mock]);

        $field = $filter->getField($fieldName);
        self::assertEquals($mock, $field);
        self::assertNull($filter->getField('dead'));
    }

    public function testSorting(): void
    {
        $filter = new Filter([]);
        $sorting = new Sorting('data', 'asc');

        $filter->setSorting($sorting);
        self::assertEquals($sorting, $filter->getSorting());
        $filter->setSorting(null);
        self::assertNull($filter->getSorting());
    }

    public function testSkipEmptyField(): void
    {
        $fieldName = 'test';
        /** @var MockObject|FieldInterface|FilterField */
        $mock = $this->getMockForAbstractClass(
            FilterField::class,
            [$fieldName],
            '',
            true,
            true,
            true,
            ['getName', 'isEmpty']
        );
        $mock->expects(self::any())->method('getName')->willReturn($fieldName);
        $mock->expects(self::any())->method('isEmpty')->willReturn(true);

        $filter = new Filter([$mock]);

        self::assertTrue($filter->isEmpty());
    }
}

