<?php

namespace App\Helpers;

enum ProductType: string
{
    case EPB = 'EPB';
    case VS = 'VS';
    case Both = 'EPB & VS';
    case Service = 'Service';
    case Other = 'Other';
}
