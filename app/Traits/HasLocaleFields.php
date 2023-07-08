<?php

namespace App\Traits;

use App\Enums\Lang;
use Illuminate\Support\Facades\App;

trait HasLocaleFields
{

    public function getName(Lang $lang = null): string
    {
        return $this->getLocaleValue('name', $lang);
    }

    public function getLocaleValue(string $attribute, Lang $lang = null, $default = null): ?string
    {
        if ($lang === null) {
            $lang = Lang::fromValue(App::getLocale());
        }
        return $this->{$attribute . '_' . $lang->value} ?? $default;
    }


}
