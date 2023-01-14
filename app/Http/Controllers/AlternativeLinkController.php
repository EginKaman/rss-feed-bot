<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlternativeLinkRequest;
use App\Http\Requests\UpdateAlternativeLinkRequest;
use App\Models\AlternativeLink;
use Illuminate\Http\Response;

class AlternativeLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAlternativeLinkRequest $request
     * @return Response
     */
    public function store(StoreAlternativeLinkRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param AlternativeLink $alternativeLink
     * @return Response
     */
    public function show(AlternativeLink $alternativeLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AlternativeLink $alternativeLink
     * @return Response
     */
    public function edit(AlternativeLink $alternativeLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAlternativeLinkRequest $request
     * @param AlternativeLink $alternativeLink
     * @return Response
     */
    public function update(UpdateAlternativeLinkRequest $request, AlternativeLink $alternativeLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AlternativeLink $alternativeLink
     * @return Response
     */
    public function destroy(AlternativeLink $alternativeLink)
    {
        //
    }
}
