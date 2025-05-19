<?php

namespace App\Http\Controllers\Api\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\Api\User\ProfileResource;
use App\Http\Services\ProfileService;
use App\Mail\TestEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(name: 'Profile')]
class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService,
    ) {}

    #[OA\Get(
        path: '/api/v1/user/show',
        summary: 'Отримати профіль користувача',
        security: [['Bearer' => []], ['OAuth2' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Профіль користувача',
                content: new OA\JsonContent(ref: '#/components/schemas/Profile')
            ),
        ]
    )]
    public function show(Request $request)
    {
        try {
            return new ProfileResource($request->user());
        } catch (\DomainException $exception) {
            return response()->json([
                'errors' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Put(
        path: '/api/v1/user',
        summary: 'Оновити профіль користувача',
        security: [['Bearer' => []], ['OAuth2' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ProfileUpdateRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Профіль оновлено',
                content: new OA\JsonContent(ref: '#/components/schemas/Profile')
            ),
        ]
    )]
    public function update(ProfileUpdateRequest $request): ProfileResource
    {
        $this->profileService->updateUser($request);
        $user = User::findOrFail($request->user()->id);

        Mail::to($user->email)->send(new TestEmail($user));

        return new ProfileResource($user);
    }
}
