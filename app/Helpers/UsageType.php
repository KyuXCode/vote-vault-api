<?php

namespace App\Helpers;

enum UsageType: string
{
    case Active = 'Active';
    case Spare = 'Spare';
    case Display = 'Display';
    case Inactive = 'Inactive';
    case Other = 'Other';
}
