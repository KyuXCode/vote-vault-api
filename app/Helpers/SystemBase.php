<?php

namespace App\Helpers;

enum SystemBase: string
{
    case DRE = 'DRE';
    case OpScan = 'OpScan';
    case Computer = 'PC/Laptop';
    case Tablet = 'Tablet';
    case CustomHardware = 'Custom Hardware';
    case Other = 'Other';
}
