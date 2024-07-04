<?php

namespace App\Http\Controllers;

use App\Http\Requests\CorCustomerLoginRequest;
use App\Http\Requests\StoreCorpCustomerAccountRequest;
use App\Http\Resources\CorporateUserRegisterResource;
use App\Http\Resources\OtpGenrateResource;
use App\Models\CorpCustomerAccount;
use App\Repositories\AuthRepository;
use App\Repositories\OtpProcessRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ResponseTrait;
    protected AuthRepository $authRepository;
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function register(StoreCorpCustomerAccountRequest $request)
    {
        try {
            return (CorporateUserRegisterResource::make($this->authRepository->register($request->all()))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'User registered Successfully']));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login(CorCustomerLoginRequest $request)
    {
        try {
            $user = $this->authRepository->login($request->all());
            if ($user) {
                return (CorporateUserRegisterResource::make($user))
                    ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'Login Successfully']);
            }
            return $this->apiResponse('error',JsonResponse::HTTP_UNAUTHORIZED,false,'Invalid credentials', 'data', null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CorpCustomerAccount $account)
    {
        try {
            return (CorporateUserRegisterResource::make($this->authRepository->uodateCorporateCustomer($request->all()))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'User registered Successfully']));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }
    public function verifyAccounts(StoreCorpCustomerAccountRequest $request)
    {
        try {
            return (CorporateUserRegisterResource::make($request->all())
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'Available for use']));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }
}
