<?php

namespace App\Helpers;

enum CertificationType: string
{
    case Certification = 'Certification';
    case Reevaluation = 'Reevaluation';
    case Renewal = 'Renewal';
    case Recertification = 'Recertification';
    case Other = 'Other';
}
