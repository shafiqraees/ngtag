<?php

namespace App\Http\Controllers\Admin;

use App\Filters\CorpCustomerAccountFilter;
use App\Filters\CorporateTagFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentReviewRequest;
use App\Http\Resources\CorporateUserRegisterResource;
use App\Models\CorpCustomerAccount;
use App\Repositories\CorporateCustomerAccountRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentReviewController extends Controller
{
    use ResponseTrait;
    protected CorporateCustomerAccountRepository $corporateCustomerAccountRepository;
    public function __construct(CorporateCustomerAccountRepository $corporateCustomerAccountRepository)
    {
        $this->corporateCustomerAccountRepository = $corporateCustomerAccountRepository;
    }

    public function allCorporateCustomer(CorpCustomerAccountFilter $filter) {
        try {
            return (CorporateUserRegisterResource::make($this->corporateCustomerAccountRepository->allCorporateCustomer($filter))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => null]));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }
    public function documentProcess(DocumentReviewRequest $request, CorpCustomerAccount $customer_account_id) {
        try {
            return (CorporateUserRegisterResource::make($this->corporateCustomerAccountRepository->documentProcess($request->all(),$customer_account_id))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => null]));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiResponse('error',JsonResponse::HTTP_INTERNAL_SERVER_ERROR,false,$exception->getMessage(), 'data', null);
        }
    }
}
