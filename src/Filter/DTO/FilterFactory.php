<?php

declare(strict_types=1);

namespace Homeapp\Filter\DTO;

use DateTime;
use Homeapp\Filter\DTO\Field\Equal;
use Homeapp\Filter\DTO\Field\FromTo;
use Homeapp\Filter\DTO\Field\In;
use Homeapp\Filter\DTO\Field\IsNotNull;
use Homeapp\Filter\DTO\Field\IsNull;
use Homeapp\Filter\DTO\Field\Like;
use Homeapp\Filter\DTO\Field\Point;
use Homeapp\Filter\DTO\Field\Polygon;
use Homeapp\Filter\DTO\Field\Rectangle;
use Symfony\Component\HttpFoundation\Request;

use function is_array;

class FilterFactory
{
    public const  COORDINATES_FIELD = 'coordinates';
    public const  POLYGON_FIELD     = 'polygon';
    private const COUNT_ALL         = 'all';

    public function fromRequest(Request $request, int $defaultCount = 30): Filter
    {
        $filter = new Filter([]);
        $array = $this->arrayFromRequest($request, $defaultCount);
        $filter = $this->fromArray($array, $filter);

        return $filter;
    }

    public function arrayFromRequest(Request $request, int $defaultCount = 30): array
    {
        $content = $request->query->all();
        $array = [
            'filter' => $content['filter'] ?? [],
        ];

        if (empty($content['limits']['count']) || self::COUNT_ALL !== $content['limits']['count']) {
            $array['limits']['page'] = (int)($content['limits']['page'] ?? $content['page'] ?? 1);
            $array['limits']['count'] = (int)($content['limits']['count'] ?? $content['count'] ?? $defaultCount);
        }
        $array['sorting'] = (!empty($content['sorting']) && is_array($content['sorting'])) ? $content['sorting'] : [];
        $array['viewType'] = (!empty($content['viewType']) && is_string($content['viewType'])) ? $content['viewType'] : null;

        return $array;
    }

    /**
     * @throws FilterFactoryQueryParseException
     */
    public function fromArray(array $array, Filter $filter = null): Filter
    {
        if (null === $filter) {
            $filter = new Filter([]);
        }

        /** @var mixed $fieldData */
        foreach ($array['filter'] as $fieldName => $fieldData) {
            if (!is_string($fieldName)) {
                continue;
            }
            if ($fieldName === 'age' && is_scalar($fieldData)) {
                $filter = $filter->withField(new FromTo('updatedAt', new DateTime('-'.$fieldData), null));
            } elseif (is_array($fieldData)) {
                if (isset($fieldData['from']) || isset($fieldData['to'])) {
                    $filter = $filter->withField(FromTo::fromArray($fieldName, $fieldData));
                } elseif (isset($fieldData[Point::LAT], $fieldData[Point::LNG])) {
                    $filter = $filter->withField(new Point($fieldName, (float)$fieldData[Point::LAT], (float)$fieldData[Point::LNG]));
                } elseif ($fieldName === self::POLYGON_FIELD) {
                    $filter = $filter->withField(new Polygon($fieldName, $fieldData));
                } elseif ($fieldName === self::COORDINATES_FIELD) {
                    $filter = $filter->withField(Rectangle::fromSquareArray($fieldName, $fieldData));
                } else {
                    $filter = $filter->withField(new In($fieldName, $fieldData));
                }
            } elseif (is_string($fieldData)) {
                if (0 === strpos($fieldData, '%')) {
                    $filter = $filter->withField(new Like($fieldName, $fieldData));
                } elseif (0 === strpos($fieldData, 'notnull')) {
                    $filter = $filter->withField(new IsNotNull($fieldName));
                } elseif (0 === strpos($fieldData, 'null')) {
                    $filter = $filter->withField(new IsNull($fieldName));
                }
            } else {
                $filter = $filter->withField(new Equal($fieldName, $fieldData));
            }
        }
        if (!empty($array['limits']['count']) && self::COUNT_ALL !== $array['limits']['count']) {
            $filter->setLimits((int)$array['limits']['page'], (int)$array['limits']['count']);
        }
        if (isset($array['viewType'])) {
            $filter->setViewType((string)$array['viewType']);
        }

        if (!empty($array['sorting'])) {
            if (
                !isset($array['sorting']['type'])
                || empty($array['sorting']['type'])
            ) {
                throw new FilterFactoryQueryParseException('Undefined `type` parameter in query');
            }

            $sorting = new Sorting((string)$array['sorting']['type'], (string)($array['sorting']['direction'] ?? 'asc'));
            $filter->setSorting($sorting);
        }

        return $filter;
    }
}
