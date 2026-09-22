<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AdminAuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private AdminAuthService $authService
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login(
                $request->validated()
            );

            if (!$result) {
                return $this->errorResponse(
                    __('messages.invalid_credentials'),
                    401
                );
            }

            return $this->successResponse([
                'admin' => [
                    'id' => $result['admin']->id,
                    'name' => $result['admin']->name,
                    'email' => $result['admin']->email,
                ],

                'token' => $result['token'],

                'token_type' => 'Bearer',
            ], __('messages.login_success'));

        } catch (Throwable $exception) {
            report($exception);

            return $this->errorResponse(
                __('messages.server_error'),
                500
            );
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());

            return $this->successResponse(
                null,
                __('messages.logout_success')
            );

        } catch (Throwable $exception) {
            report($exception);

            return $this->errorResponse(
                __('messages.server_error'),
                500
            );
        }
    }
}