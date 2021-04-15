<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Filter as DTOFilter;
use Homeapp\Filter\DTO\Sorting;
use Homeapp\FilteringRepository\Filter;
use Homeapp\FilteringRepository\Handlers\FilteringHandlerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class FilterTest extends TestCase
{
    public function test(): void
    {
        $filter = new Filter();
        $reflection = new ReflectionClass($filter);
        $reflection->getProperty('allowedFields')->setAccessible(true);
        $reflection->getProperty('virtualFields')->setAccessible(true);
        $reflection->getProperty('sortingFields')->setAccessible(true);
    }

    public function testNoSort(): void
    {
        $filter = new Filter();

        /** @var DTOFilter|MockObject */
        $filterData = $this->createPartialMock(DTOFilter::class, ['getSorting']);
        $filterData->expects(self::never())->method('getSorting');

        /** @var QueryBuilder|MockObject */
        $queryBuilder = $this->createStub(QueryBuilder::class);

        $filter->prepareFilter($filterData, $queryBuilder, true);
    }

    public function testSort(): void
    {
        $filter = new Filter();

        $sortingType = 'test';
        $sortingDirection = 'asc';

        $reflection = new ReflectionClass($filter);
        $property = $reflection->getProperty('sortingFields');
        $property->setAccessible(true);
        $property->setValue($filter, [$sortingType]);

        /** @var DTOFilter|MockObject */
        $filterData = $this->createPartialMock(DTOFilter::class, ['getSorting']);
        $filterData->expects(self::once())
            ->method('getSorting')
            ->willReturn(new Sorting($sortingType, $sortingDirection));

        /** @var QueryBuilder|MockObject */
        $queryBuilder = $this->createPartialMock(QueryBuilder::class, ['orderBy']);
        $queryBuilder->expects(self::once())
            ->method('orderBy')
            ->with("a.{$sortingType}", $sortingDirection);

        $filter->prepareFilter($filterData, $queryBuilder, false);
    }

    public function testNotAllowedField(): void
    {
        $filter = new Filter();

        /** @var DTOFilter|MockObject */
        $filterData = $this->createPartialMock(DTOFilter::class, ['getFields']);

        $field = $this->getMockForAbstractClass(FilterField::class, ['fieldName']);

        $filterData->expects(self::once())->method('getFields')->willReturn([$field]);

        /** @var QueryBuilder|MockObject */
        $queryBuilder = $this->createStub(QueryBuilder::class);

        $filter->prepareFilter($filterData, $queryBuilder, false);
    }

    public function testAllowedFieldHandlerNotSupported(): void
    {
        $fieldName = 'fieldName';

        /** @var FilteringHandlerInterface|MockObject */
        $filterHandler = $this->createMock(FilteringHandlerInterface::class);
        $filterHandler->expects(self::once())
            ->method('isSupported')
            ->willReturn(false);

        $filterHandler->expects(self::never())
            ->method('addFilter');

        $filter = new Filter($filterHandler);
        $reflection = new ReflectionClass($filter);
        $property = $reflection->getProperty('allowedFields');
        $property->setAccessible(true);
        $property->setValue($filter, [$fieldName]);

        /** @var DTOFilter|MockObject */
        $filterData = $this->createPartialMock(DTOFilter::class, ['getFields']);

        $field = $this->getMockForAbstractClass(FilterField::class, [$fieldName]);

        $filterData->expects(self::once())->method('getFields')->willReturn([$field]);

        /** @var QueryBuilder|MockObject */
        $queryBuilder = $this->createStub(QueryBuilder::class);

        $filter->prepareFilter($filterData, $queryBuilder, false);
    }

    public function testAllowedFieldHandlerSupported(): void
    {
        $fieldName = 'fieldName';

        /** @var QueryBuilder|MockObject */
        $queryBuilder = $this->createStub(QueryBuilder::class);

        $field = $this->getMockForAbstractClass(FilterField::class, [$fieldName]);

        /** @var FilteringHandlerInterface|MockObject */
        $filterHandler = $this->createMock(FilteringHandlerInterface::class);
        $filterHandler->expects(self::once())
            ->method('isSupported')
            ->willReturn(true);

        $filterHandler->expects(self::once())
            ->method('addFilter')
            ->with($field, $queryBuilder);

        $filter = new Filter($filterHandler);
        $reflection = new ReflectionClass($filter);
        $property = $reflection->getProperty('allowedFields');
        $property->setAccessible(true);
        $property->setValue($filter, [$fieldName]);

        /** @var DTOFilter|MockObject */
        $filterData = $this->createPartialMock(DTOFilter::class, ['getFields']);

        $filterData->expects(self::once())->method('getFields')->willReturn([$field]);

        $filter->prepareFilter($filterData, $queryBuilder, false);
    }

    public function testAllowedFieldHandlerIsFinal(): void
    {
        $fieldName = 'fieldName';

        /** @var QueryBuilder|MockObject */
        $queryBuilder = $this->createStub(QueryBuilder::class);

        $field = $this->getMockForAbstractClass(FilterField::class, [$fieldName]);

        /** @var FilteringHandlerInterface|MockObject */
        $filterHandler = $this->createMock(FilteringHandlerInterface::class);
        $filterHandler->expects(self::once())
            ->method('isSupported')
            ->willReturn(true);

        $filterHandler->expects(self::once())
            ->method('addFilter')
            ->with($field, $queryBuilder);

        $filterHandler->expects(self::once())
            ->method('isFinal')
            ->willReturn(true);

        $filter = new Filter($filterHandler);
        $reflection = new ReflectionClass($filter);
        $property = $reflection->getProperty('allowedFields');
        $property->setAccessible(true);
        $property->setValue($filter, [$fieldName]);

        /** @var DTOFilter|MockObject */
        $filterData = $this->createPartialMock(DTOFilter::class, ['getFields']);

        $filterData->expects(self::once())->method('getFields')->willReturn([$field]);

        $filter->prepareFilter($filterData, $queryBuilder, false);
    }
}
