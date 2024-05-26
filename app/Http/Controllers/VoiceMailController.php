<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoiceMailRequest;
use App\Http\Requests\UpdateVoiceMailRequest;
use App\Models\VoiceMail;

class VoiceMailController extends Controller
{
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
