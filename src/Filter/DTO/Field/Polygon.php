<?php declare(strict_types=1);

namespace Homeapp\Filter\DTO\Field;

class Polygon extends FilterField
{
    private ?array $geoJson;

    public function __construct(string $name, array $geoJson)
    {
        parent::__construct($name);
        $this->geoJson = $geoJson['features'][0]['geometry'] ?? null;
    }

    public function isEmpty(): bool
    {
        return empty($this->geoJson);
    }

    /**
     * @return array|null
     */
    public function getValue()
    {
        return $this->getGeoJson();
    }

    public function getGeoJson(): ?array
    {
        return $this->geoJson;
    }
}
