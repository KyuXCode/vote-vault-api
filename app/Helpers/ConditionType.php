<?php

namespace App\Helpers;

enum ConditionType: string
{
    case New = 'New';
    case Excellent = 'Excellent';
    case Good = 'Good';
    case Worn = 'Worn';
    case Damaged = 'Damaged';
    case Unusable = 'Unusable';
}
