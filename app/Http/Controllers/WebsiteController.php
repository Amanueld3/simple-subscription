<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWebsiteRequest;
use App\Http\Requests\UpdateWebsiteRequest;
use App\Models\Website;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => Website::paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function get_all()
    {
        return response()->json(['data' => Website::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteRequest $request)
    {
        DB::beginTransaction();

        try {
            $website = Website::create([
                'name' => $request->name,
                'url' => $request->url,
            ]);
            DB::commit();

            return $website;
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Website $website)
    {
        return $website;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Website $website)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteRequest $request, Website $website)
    {
        $website->update([
            'name' => $request->name ?? $website->name,
            'url' => $request->url ?? $website->url,
        ]);

        return response()->json(['message' => 'updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Website $website)
    {
        $website->delete();

        return response()->json(['message' => 'deleted']);
    }
}
