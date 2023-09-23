<?php

namespace App\Enums;

enum DietaryRestrictionTypeEnum: string
{
    use EnumHelper;

    case Allergy = 'allergy';
    case Diet = 'diet';
}
