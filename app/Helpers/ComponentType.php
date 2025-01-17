<?php

namespace App\Helpers;

enum ComponentType: string
{
    case DRE = 'DRE';
    case OpScan = 'OpScan';
    case BMD = 'BMD';
    case VVPAT = 'VVPAT';
    case COTS = 'COTS';
    case Other = 'Other';
    case Hardware = 'Hardware';
    case Software = 'Software';
    case Peripheral = 'Peripheral';
}
