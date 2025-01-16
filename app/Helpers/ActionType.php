<?php

namespace App\Helpers;

enum ActionType: string
{
    case Approved = 'Approved';
    case Pending = 'Pending';
    case Denied = 'Denied';
    case Other = 'Other';
}
