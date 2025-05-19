<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * Реєстрація нового користувача
     *
     * @OA\Post(
     *     path="/register",
     *     tags={"Auth"},
     *     summary="Реєстрація нового користувача",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Користувача створено. Перевірте емейл",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="string", example="перевірте емейл і підтвердіть пошту")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $this->userService->createUser($request);

        return response()->json([
            'success' => 'перевірте емейл і підтвердіть пошту',
        ], ResponseAlias::HTTP_CREATED);
    }
}
