<?php

namespace App\Http\Controllers;

use App\Filters\CorporateSubcribeFilter;
use App\Http\Requests\StoreCorpSubscriberRequest;
use App\Http\Requests\UpdateCorpSubscriberRequest;
use App\Http\Resources\CorporateTagResource;
use App\Models\CorpSubscriber;
use App\Repositories\CorporateSubscriberRepository;
use App\Traits\ResponseTrait;

class CorpSubscriberController extends Controller
{
    use ResponseTrait;
    /**
     * @var CorporateSubscriberRepository
     */
    protected CorporateSubscriberRepository $corporateSubscriberRepository;

    /**
     * @param CorporateSubscriberRepository $corporateSubscriberRepository
     */
    public function __construct(CorporateSubscriberRepository $corporateSubscriberRepository)
    {
        $this->corporateSubscriberRepository = $corporateSubscriberRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(CorporateSubcribeFilter $filter)
    {
        try {
            return (CorporateTagResource::collection($this->corporateSubscriberRepository->index($filter))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true]));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiErrorResponse($exception);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCorpSubscriberRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CorpSubscriber $corpSubscriber)
    {
        try {
            return (CorporateTagResource::make($corpSubscriber)
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true]));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiErrorResponse($exception);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCorpSubscriberRequest $request, CorpSubscriber $corpSubscriber)
    {
        try {
            return (CorporateTagResource::make($this->corporateSubscriberRepository->updateSubscriber($request->all(),$corpSubscriber))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true,'message' => 'You have successfully updated incoming calls schedule']));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiErrorResponse($exception);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CorpSubscriber $corpSubscriber)
    {
        //
    }
}
