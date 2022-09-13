<?php

namespace IMSExport\Core;

use IMSExport\Core\Connection\BaseModel;

class BaseEntity
{
    protected array $data = [];

    protected BaseModel $repository;

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        if ($this->{$name}) {
            return $this->{$name};
        }
        if (method_exists($this, $name)) {
            return $this->$name();
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function setAttribute($key, $value): BaseEntity
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function getAttribute(string $attribute) : mixed
    {
        return $this->data[$attribute];
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function search($resource, $collection = false): array | null
    {
        $data = $this->repository->getData($resource);
        if ($collection) {
            return Collection::collection($data);
        }
        return $data;
    }

    public function firstElement($resource): array | null
    {
        return $this->repository->firstElement($resource);
    }

    public function setData($data): void
    {
        $this->data = $data;
    }
}