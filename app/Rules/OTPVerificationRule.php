<?php

namespace App\Rules;

use App\Enums\OTPType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\OtpProcess;

class OTPVerificationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $otp = OtpProcess::where('otp_id',request()->input('otp_id'))
            ->where('otp_code', $value)
            ->where('status', 0) // Only pending OTPs can be verified
            ->where('expiration_time', '>', now()) // Check if OTP is not expired
            ->first();

        if ($otp) {
            $otp->status = 1; // 1 = verified
            $otp->verify_date = now();
            $otp->save();
        } else {
            $fail('The provided OTP is invalid or has expired.');
        }
    }
}
