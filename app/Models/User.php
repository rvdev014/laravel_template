<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Lang;
use App\Enums\UserStatus;
use App\Traits\UserTrait;
use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $birth_date
 * @property string $email
 * @property string $email_verified_at
 * @property string $confirm_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property ?Lang $language
 * @property ?string $avatar
 * @property ?Gender $gender
 * @property UserStatus $status
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 *
 * @property-read NotificationSettings $notificationSettings
 *
 * @mixin Builder
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use UserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'email',
        'language',
        'gender',
        'password_hash',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'confirm_token',
        'password_reset_token',
        'updated_at',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'language' => Lang::class,
        'status' => UserStatus::class,
        'gender' => Gender::class,
        'email_verified_at' => 'datetime',
    ];


    public function createToken(
        string $name,
        string $fcm_token = null,
        array $abilities = ['*'],
        DateTimeInterface $expiresAt = null
    ): NewAccessToken {
        /** @var PersonalAccessToken $token */

        $token = $this->tokens()->updateOrCreate(
            ['device_name' => $name],
            [
                'device_name' => $name,
                'fcm_token' => $fcm_token,
                'token' => hash('sha256', $plainTextToken = Str::random(40)),
                'abilities' => $abilities,
                'expires_at' => $expiresAt,
            ]
        );

        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }

    public function notificationSettings(): HasOne
    {
        return $this->hasOne(NotificationSettings::class);
    }

    public function reads(): HasMany
    {
        return $this->hasMany(ComicsRead::class, 'comics_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ComicsReview::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
