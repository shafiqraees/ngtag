<?php

namespace App\Http\Controllers;

use App\Filters\VoiceMailFilter;
use App\Http\Requests\StoreVoiceMailRequest;
use App\Http\Requests\UpdateVoiceMailRequest;
use App\Http\Resources\CorporateTagResource;
use App\Models\CorpTagList;
use App\Models\VoiceMail;
use App\Repositories\CorporateTagRepository;
use App\Repositories\VoiceMailRepository;
use App\Traits\ResponseTrait;

class VoiceMailController extends Controller
{
    use ResponseTrait;
    /**
     * @var VoiceMailRepository
     */
    protected VoiceMailRepository $voiceMailRepository;

    /**
     * @param VoiceMailRepository $voiceMailRepository
     */
    public function __construct(VoiceMailRepository $voiceMailRepository)
    {
        $this->voiceMailRepository = $voiceMailRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(VoiceMailFilter $filter)
    {
        try {
            return (CorporateTagResource::collection($this->voiceMailRepository->index($filter))
                ->additional(['response_status' => "success", 'response_code' => "00",'success' => true]));
        } catch (\Exception $exception) {
            report($exception);
            return $this->apiErrorResponse($exception);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoiceMailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(VoiceMail $voiceMail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoiceMailRequest $request, VoiceMail $voiceMail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VoiceMail $voiceMail)
    {
        //
    }
}
