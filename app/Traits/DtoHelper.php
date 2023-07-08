<?php

namespace App\Traits;

trait DtoHelper
{
    protected array $filled = [];

    public function filled(): array
    {
        return $this->filled;
    }

    public static function fromArray(array $array): self
    {
        return new self($array);
    }
}
