<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UserService
{
    use ApiResponse;

    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function delete(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    /**
     * @throws Exception
     */
    public function updateUser(User $user, array $attributes, bool $isRemoveAvatar = false): User
    {
        /** @var UploadedFile $avatar */
        $avatar = $attributes['avatar'] ?? null;
        $email = $attributes['email'] ?? null;

        try {
            if ($this->isEmailChanged($user, $email)) {
                $user->email_verified_at = null;
            }

            if ($isRemoveAvatar && empty($avatar)) {
                $this->removeUserAvatar($user);
                $user->avatar = null;
            }

            if (!empty($avatar)) {
                $user->avatar = $this->storeUserAvatar($user, $avatar);
            }

            DB::beginTransaction();

            $user->updateOrFail($attributes);
            $user->refresh();

            if (!$user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
            }

            DB::commit();

            if ($user->language) {
                App::setLocale($user->language->value);
            }

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    private function isEmailChanged(User $user, $newEmail): bool
    {
        return !empty($newEmail) && $user->email !== $newEmail;
    }

    private function storeUserAvatar(User $user, UploadedFile $avatar): string
    {
        $this->removeUserAvatar($user);
        return $avatar->store('avatars', 'public');
    }

    private function removeUserAvatar(User $user): void
    {
        $storage = Storage::disk('public');

        if (!empty($user->avatar)) {
            if ($storage->fileExists($user->avatar)) {
                $storage->delete($user->avatar);
            }
        }
    }

    public function deleteAccount(User $user): bool
    {
        $user->tokens()->delete();
        return $user->delete();
    }

    /**
     * @throws Throwable
     */
    public function changePassword(User $user, string $new_password): bool
    {
        $user->password_hash = Hash::make($new_password);
        return $user->saveOrFail();
    }
}
