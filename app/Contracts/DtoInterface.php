<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface DtoInterface
{
    public function filled(): array;

    public static function fromArray(array $array): self;
}
