<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\Polygon;
use Doctrine\ORM\QueryBuilder;

class PolygonHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof Polygon;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof Polygon) {
            $qb->andWhere(
                sprintf(
                    'ST_Contains( ST_GeomFromGeoJSON(:%sPoly), ST_Point(' . FilteringHandlerInterface::DEFAULT_ALIAS . '.lng, ' . FilteringHandlerInterface::DEFAULT_ALIAS . ".lat)) = 't'",
                    $field->getName()
                )
            )->setParameter(sprintf('%sPoly', $field->getName()), \json_encode($field->getGeoJson()));
        }
    }
}
