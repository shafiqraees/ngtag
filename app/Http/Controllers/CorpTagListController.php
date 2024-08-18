<?php

namespace App\Http\Controllers;

use App\Filters\CorporateTagFilter;
use App\Http\Requests\StoreCorpTagListRequest;
use App\Http\Requests\UpdateCorpTagListRequest;
use App\Http\Resources\CorporateTagResource;
use App\Models\CorpTagList;
use App\Repositories\CorporateTagRepository;
use App\Traits\ResponseTrait;


class CorpTagListController extends Controller
{
    use ResponseTrait;
    /**
     * @var CorporateTagRepository
     */
    protected CorporateTagRepository $corporateTagRepository;

    /**
     * @param CorporateTagRepository $corporateTagRepository
     */
    public function __construct(CorporateTagRepository $corporateTagRepository)
    {
        $this->corporateTagRepository = $corporateTagRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CorporateTagFilter $filter)
    {
        try {
            return (CorporateTagResource::collection($this->corporateTagRepository->index($filter))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true]));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiErrorResponse($exception);
        }
    }
    public function getUniqueTagDigits(CorporateTagFilter $filter)
    {
        try {
            return (CorporateTagResource::collection($this->corporateTagRepository->getUniqueTagDigits($filter))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true]));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiErrorResponse($exception);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCorpTagListRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CorpTagList $corpTagList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCorpTagListRequest $request, CorpTagList $corpTagList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CorpTagList $corpTagList)
    {
        //
    }
}
