<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    public static $wrap = false;

    public function toArray(Request $request): array
    {
        return array_merge(
            parent::toArray($request),
            [
                'birth_date' => $this->whenNotNull($this->birth_date),
                'email_verified_at' => $this->whenNotNull($this->email_verified_at),
                'avatar' => $this->when($this->avatar, asset('storage/' . $this->avatar)),
                'gender' => $this->whenNotNull($this->gender?->getLabel()),
                'language' => $this->whenNotNull($this->language?->getLocale()),
                'verified' => $this->hasVerifiedEmail(),
                'notification_settings' => NotificationSettingsResource::collection(
                    $this->whenLoaded('notificationSettings')
                ),
            ]
        );
    }

}
