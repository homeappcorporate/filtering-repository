<?php

declare(strict_types=1);

namespace Homeapp\Filter\DTO;

use Homeapp\Filter\DTO\Field\FieldInterface;
use Homeapp\Filter\DTO\Field\FilterField;

class Filter
{
    /**
     * @var array|FilterField[]
     */
    private $fields = [];

    private $page;

    private $count;

    /** @var string|null */
    private $viewType;

    /**
     * @var Sorting|null
     */
    private $sorting;

    /** @var string|null */
    private $groupBy;

    public function __construct(array $fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    public function removePaging(): void
    {
        $this->count = null;
        $this->page = null;
    }

    public function setLimits(int $page, int $count): void
    {
        $this->page = $page;
        $this->count = $count;
    }

    public function getViewType(): ?string
    {
        return $this->viewType;
    }

    public function setViewType(?string $viewType): void
    {
        $this->viewType = $viewType;
    }

    public function getGroupBy(): ?string
    {
        return $this->groupBy;
    }

    public function setGroupBy(?string $groupBy): void
    {
        $this->groupBy = $groupBy;
    }

    public function getPage(): int
    {
        return $this->page ?? 1;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function withoutField($name): Filter
    {
        $o = clone $this;
        if (isset($o->fields[$name])) {
            unset($o->fields[$name]);
        }

        return $o;
    }

    public function hasField(string $field): bool
    {
        return isset($this->fields[$field]);
    }

    public function doesNotHaveField(string $field): bool
    {
        return !$this->hasField($field);
    }

    /**
     * @param FieldInterface $filterField
     *
     * @deprecated используйте иммутабельный метод withField()
     */
    public function addField(FieldInterface $filterField): void
    {
        if ($filterField->isEmpty()) {
            return;
        }
        $this->fields[$filterField->getName()] = $filterField;
    }

    /**
     * @param string $fieldName
     *
     * @deprecated используйте иммутабельный метод withoutField()
     */
    public function removeField(string $fieldName): void
    {
        if (array_key_exists($fieldName, $this->fields)) {
            unset($this->fields[$fieldName]);
        }
    }

    public function withField(FieldInterface $filterField): self
    {
        $obj = clone $this;
        $obj->addField($filterField);

        return $obj;
    }

    /**
     * @return  array|FilterField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function getField(string $name): ?FieldInterface
    {
        return $this->fields[$name] ?? null;
    }

    public function getSorting(): ?Sorting
    {
        return $this->sorting;
    }

    public function setSorting(?Sorting $sorting): void
    {
        $this->sorting = $sorting;
    }

    public function isEmpty(): bool
    {
        return empty($this->fields);
    }
}
