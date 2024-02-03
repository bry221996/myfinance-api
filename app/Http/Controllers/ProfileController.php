<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Spatie\QueryBuilder\QueryBuilder;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = QueryBuilder::for(Profile::class)
            ->whereUserId(auth()->id())
            ->paginate();

        return ProfileResource::collection($profiles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileRequest $request)
    {
        $profile = Profile::create([
            ...$request->validated(),
            'user_id' => auth()->id()
        ]);

        return ProfileResource::make($profile);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $profile = Profile::whereId($id)
            ->whereUserId(auth()->id())
            ->firstOrFail();

        return ProfileResource::make($profile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileRequest $request, Profile $profile)
    {
        $profile->update($request->validated());

        return ProfileResource::make($profile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $profile = Profile::whereId($id)
            ->whereUserId(auth()->id())
            ->firstOrFail();

        $profile->delete();

        return response([]);
    }
}
