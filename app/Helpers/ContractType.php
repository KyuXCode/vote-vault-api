<?php

namespace App\Helpers;

enum ContractType: string
{
    case Purchase = 'Purchase';
    case Lease = 'Lease';
    case Service = 'Service';
    case Other = 'Other';
}
