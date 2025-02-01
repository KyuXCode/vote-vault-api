<?php

namespace App\Helpers;

enum AccountStatusType: string
{
    case Active = 'Active';
    case Pending = 'Pending';
    case Disable = 'Disable';
}
