<?php

namespace App\Repositories;

use App\Models\OtpProcess;
use Illuminate\Support\Str;
class OtpProcessRepository
{
    /**
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    public function generateOtp(array $data) {
        try {
            $otp_code = rand(1000, 9999);
            //$otp_id = (int)(microtime(true) * 1000); // Generate a 10-digit sequence ID
            $otp_id = Str::uuid()->toString();; // Generate a 10-digit sequence ID
            return OtpProcess::create([
                'otp_code' => $otp_code,
                'otp_id' => $otp_id,
                'msisdn' => $data['msisdn'],
                'otp_type' => $data['otp_type'],
                'channel' => $data['channel'],
                'expiration_time' => now()->addMinutes(5),
                'status' => 0, // pending
            ]);

        } catch (\Throwable $exception ) {
            report($exception);
            throw $exception;
        }
    }
    public function verifyOtp(array $data) {
        try {
             $otp = OtpProcess::where('otp_id', $data['otp_id'])
                ->where('otp_code', $data['otp_code'])
                ->where('status', 0) // Only pending OTPs can be verified
                ->where('expiration_time', '>', now()) // Check if OTP is not expired
                ->first();
             if ($otp) {
                 $otp->status = 1; // 1 = verified
                 $otp->verify_date = now();
                 $otp->save();
             }
             return $otp;
        } catch (\Throwable $exception ) {
            report($exception);
            throw $exception;
        }
    }
}
