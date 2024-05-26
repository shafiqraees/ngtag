<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOtpProcessRequest;
use App\Http\Requests\UpdateOtpProcessRequest;
use App\Http\Resources\OtpGenrateResource;
use App\Http\Resources\OtpVerifyResource;
use App\Models\OtpProcess;
use App\Repositories\OtpProcessRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class OtpProcessController extends Controller
{
    use ResponseTrait;

    /**
     * @var OtpProcessRepository
     */
    protected OtpProcessRepository $otpProcessRepository;

    /**
     * @param OtpProcessRepository $otpProcessRepository
     */
    public function __construct(OtpProcessRepository $otpProcessRepository)
    {
        $this->otpProcessRepository = $otpProcessRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOtpProcessRequest $request)
    {
        try {
            return (OtpGenrateResource::make($this->otpProcessRepository->generateOtp($request->all()))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'OTP Successfully sent']));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OtpProcess $otpProcess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOtpProcessRequest $request, OtpProcess $otpProcess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OtpProcess $otpProcess)
    {
        //
    }

    /**
     * @param UpdateOtpProcessRequest $request
     * @return OtpVerifyResource|JsonResponse|object
     * @throws \Throwable
     */
    public function verifyOtp(UpdateOtpProcessRequest $request)
    {
        try {
            $data = $this->otpProcessRepository->verifyOtp($request->all());
            if ($data) {
                return (OtpVerifyResource::make($data)
                    ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'OTP verified successfully']));
            }
            return $this->apiResponse('error',JsonResponse::HTTP_NOT_FOUND,false,'OTP already used or expired', 'data', null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }
}
