<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class UserController extends ApiController
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    public function me(Request $request): JsonResponse
    {
        return $this->successResponse(
            new UserResource($request->user()),
            __('messages.user.retrieved')
        );
    }

    public function deleteAccount(UserRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->email !== $request->email) {
            return $this->errorResponse(
                [
                    'email' => [
                        'field' => 'email',
                        'message' => __('messages.user.not_your_email')
                    ]
                ],
                __('messages.user.not_your_email'),
                422
            );
        }

        if (!$user->checkPassword($request->password)) {
            return $this->errorResponse(
                [
                    'password' => [
                        'field' => 'password',
                        'message' => __('messages.password_incorrect')
                    ]
                ],
                __('messages.password_incorrect'),
                422
            );
        }

        if ($this->userService->deleteAccount($user)) {
            return $this->successResponse([], __('messages.user.deleted_successfully'));
        }

        return $this->errorResponse([], __('messages.something_went_wrong'));
    }

    public function update(UserRequest $request): JsonResponse
    {
        try {
            $savedUser = $this->userService->updateUser(
                $request->user(),
                $request->validated(),
                (bool)$request->remove_avatar
            );

            return $this->successResponse(
                new UserResource($savedUser),
                __('messages.user.updated_successfully')
            );
        } catch (Throwable $e) {
            return $this->errorResponse([], $e->getMessage(), $e->getCode());
//            return $this->errorResponse([], __('messages.something_went_wrong'));
        }
    }

    public function changePassword(UserRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        /*$oldPassword = $request->old_password;

        if (!$user->checkPassword($oldPassword)) {
            return $this->errorResponse([], __('validation.old_password_incorrect'));
        }*/

        try {
            $this->userService->changePassword($user, $request->password);

            return $this->successResponse([], __('messages.user.password_changed_successfully'));
        } catch (Throwable $e) {
            return $this->errorResponse([], $e->getMessage(), $e->getCode());
//            return $this->errorResponse([], __('messages.something_went_wrong'));
        }
    }

    public function sendEmailVerification(Request $request): JsonResponse
    {
        try {
            /** @var User $user */
            $user = $request->user();
            if ($user->hasVerifiedEmail()) {
                return $this->successResponse([], __('messages.user.email_already_verified'));
            }

            $user->sendEmailVerificationNotification();

            return $this->successResponse([], __('messages.user.verification_sent'));
        } catch (Throwable $e) {
            return $this->errorResponse([], $e->getMessage(), $e->getCode());
//            return $this->errorResponse([], __('messages.something_went_wrong'));
        }
    }
}
