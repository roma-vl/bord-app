<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\User\UserFilterRequest;
use App\Http\Resources\Api\User\UserResource;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function index(UserFilterRequest $request)
    {
        $validated = $request->validatedWithDefaults();
        $users = $this->userRepository->getFilteredPaginatedUsers($validated);

        return response()->json(UserResource::collection($users));
    }
}
