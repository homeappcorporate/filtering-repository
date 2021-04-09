<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Homeapp\Filter\DTO\Field\FilterField;
use Doctrine\ORM\QueryBuilder;

interface FilteringHandlerInterface
{
    const DEFAULT_ALIAS = 'a';
    public function isFinal(): bool;
    public function isSupported(FilterField $field): bool;
    public function addFilter(FilterField $field, QueryBuilder $qb): void;
}
