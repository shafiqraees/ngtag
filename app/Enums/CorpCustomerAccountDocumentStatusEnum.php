<?php

namespace App\Enums;

enum CorpCustomerAccountDocumentStatusEnum: string
{
    case PENDING_FOR_APPROVAL = '0';
    case APPROVED = '1';
    case REJECTED = '2';

    public function label(): string
    {
        return match($this) {
            self::PENDING_FOR_APPROVAL => 'Pending for Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }
}
