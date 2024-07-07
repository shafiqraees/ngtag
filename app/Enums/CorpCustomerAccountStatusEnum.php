<?php

namespace App\Enums;

enum CorpCustomerAccountStatusEnum: string
{
    case PENDING_FOR_APPROVAL = '0';
    case APPROVED = '1';
    case REJECTED = '2';
    case BLOCKED_BY_ADMIN = '4';
    case SUSPENDED_BY_NON_PAYMENT = '5';
    case BLOCKED_DUE_TO_WRONG_PASSWORD_ATTEMPT = '6';

    public function label(): string
    {
        return match($this) {
            self::PENDING_FOR_APPROVAL => 'Pending for Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::BLOCKED_BY_ADMIN => 'Blocked by Admin',
            self::SUSPENDED_BY_NON_PAYMENT => 'Suspended by Non-payment',
            self::BLOCKED_DUE_TO_WRONG_PASSWORD_ATTEMPT => 'Blocked due to Wrong Password Attempt',
        };
    }
}
