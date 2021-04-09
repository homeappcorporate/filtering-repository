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
    private array $fields = [];

    private ?int $page  = null;
    private ?int $count = null;

    private ?string  $viewType = null;
    private ?Sorting $sorting  = null;
    private ?string  $groupBy  = null;

    public function __construct(array $fields)
    {
        foreach ($fields as $field) {
            if (!$field instanceof FieldInterface) {
                continue;
            }
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

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function withoutField(string $name): Filter
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

    public function withField(FieldInterface $filterField): self
    {
        $obj = clone $this;
        $obj->addField($filterField);

        return $obj;
    }

    /**
     * @return FilterField[]
     */
    public function getFields(): array
    {
        return array_filter($this->fields, fn($el) => $el instanceof FilterField);
    }

    public function getField(string $name): ?FieldInterface
    {
        if (!isset($this->fields[$name])) {
            return null;
        }

        return $this->fields[$name] instanceof FieldInterface ? $this->fields[$name] : null;
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

    private function addField(FieldInterface $filterField): void
    {
        if ($filterField->isEmpty()) {
            return;
        }
        $this->fields[$filterField->getName()] = $filterField;
    }
}
