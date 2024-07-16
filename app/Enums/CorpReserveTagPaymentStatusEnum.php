<?php

namespace App\Enums;

enum CorpReserveTagPaymentStatusEnum: string
{
    case PENDING_FOR_PAYMENT = '0';
    case PAYMENT_SUCCESS = '1';
    case PAYMENT_FAILED = '2';
    case EXPIRED_PAYMENT_TIMELINE = '3';


    public function label(): string
    {
        return match($this) {
            self::PENDING_FOR_PAYMENT => 'pending for payment',
            self::PAYMENT_SUCCESS => 'payment success',
            self::PAYMENT_FAILED => 'Payment Failed',
            self::EXPIRED_PAYMENT_TIMELINE => 'expired payment timeline',
        };
    }
}
