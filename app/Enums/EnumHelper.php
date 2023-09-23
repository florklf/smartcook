<?php

namespace App\Enums;

use Arr;

trait EnumHelper
{
    public static function values(): array
    {
        return Arr::pluck(self::cases(), 'value');
    }

    public static function names(): array
    {
        return Arr::pluck(self::cases(), 'name');
    }

    public static function forSelect($lang = null): array
    {
        return \array_reduce(self::cases(), function ($acc, $v) use ($lang) {
            $acc[$v->value] = $lang ? __($lang.'.'.$v->value) : $v->value;

            return $acc;
        }, []);
    }
}
