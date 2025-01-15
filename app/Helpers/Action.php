<?php

namespace App\Helpers;

enum Action: string
{
    case Approved = 'Approved';
    case Pending = 'Pending';
    case Denied = 'Denied';
    case Other = 'Other';
}
