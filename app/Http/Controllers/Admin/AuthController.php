<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\CorCustomerLoginRequest;
use App\Http\Requests\StoreCorpCustomerAccountRequest;
use App\Http\Resources\CorporateUserRegisterResource;
use App\Models\CorpCustomerAccount;
use App\Repositories\AuthRepository;
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
     * Show the form for creating a new resource.
     */
    public function adminLogin(AdminLoginRequest $request)
    {
        try {
            $user = $this->authRepository->adminLogin($request->all());
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
}
