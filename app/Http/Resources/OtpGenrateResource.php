<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OtpGenrateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'otp_id' => $this->when($this->otp_id,$this->otp_id),
            'msisdn' => $this->when($this->msisdn,$this->msisdn),
            'otp_type' => $this->when($this->otp_type,$this->otp_type),
            'channel' => $this->when($this->channel,$this->channel),
            'status' => $this->when($this->status,$this->status),
            'otp_code' => $this->when($this->otp_code,$this->otp_code),
            'expiration_time' => $this->when($this->expiration_time,$this->expiration_time),
        ];
    }
}
