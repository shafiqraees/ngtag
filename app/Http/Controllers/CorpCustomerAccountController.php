<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCorpCustomerAccountRequest;
use App\Http\Requests\StoreCorpTagListRequest;
use App\Http\Requests\UpdateCorpCustomerAccountRequest;
use App\Http\Resources\CorporateUserRegisterResource;
use App\Models\CorpCustomerAccount;
use App\Models\CorpReserveTag;
use App\Repositories\CorporateCustomerAccountRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CorpCustomerAccountController extends Controller
{
    use ResponseTrait;
    protected CorporateCustomerAccountRepository $corporateCustomerAccountRepository;
    public function __construct(CorporateCustomerAccountRepository $corporateCustomerAccountRepository)
    {
        $this->corporateCustomerAccountRepository = $corporateCustomerAccountRepository;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function buyReserveTags(StoreCorpTagListRequest $request)
    {
        try {
            return (CorporateUserRegisterResource::make($this->corporateCustomerAccountRepository->buyOrReserveTagNumber($request->all()))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'You have successfully reserve number']));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }
    public function getTagDetail(CorpReserveTag $tag_id)
    {
        try {
            return (CorporateUserRegisterResource::make($tag_id))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'Account updated successfully']);
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCorpCustomerAccountRequest $request, CorpCustomerAccount $customer_account_id)
    {
        try {
            return (CorporateUserRegisterResource::make($this->corporateCustomerAccountRepository->uodateCorporateCustomer($request->all(),$customer_account_id))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'Account updated successfully']));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }
    public function checkDocumentsVerification(CorpCustomerAccount $customer_account_id)
    {
        try {
            return (CorporateUserRegisterResource::make($customer_account_id))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'Documents verification']);
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }

}
