<?php

namespace App\Enums;

enum CorpReserveTagStatusEnum: string
{
    case RESERVE_DUE_TO_DOCUMENTS = '0';
    case BUY = '2';
    case ACTIVE = '1';
    case PENDING_FOR_PAYMENT = '3';
    case EXPIRED_DOCS = '4';
    case EXPIRED_PAYMENT = '5';
    case BLOCKED_BY_ADMIN = '6';

    public function label(): string
    {
        return match($this) {
            self::RESERVE_DUE_TO_DOCUMENTS => 'Reserve due to documents',
            self::BUY => 'Buy',
            self::ACTIVE => 'Active',
            self::PENDING_FOR_PAYMENT => 'Pending for payment',
            self::EXPIRED_DOCS => 'Expired due to documents',
            self::EXPIRED_PAYMENT => 'Expired due to payment',
            self::BLOCKED_BY_ADMIN => 'Blocked by admin',
        };
    }
}
