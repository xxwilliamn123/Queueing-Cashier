<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /** @var list<string> */
    public const ALLOWED_THEMES = [
        'blue-theme',
        'light',
        'dark',
        'semi-dark',
        'bodered-theme',
    ];

    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'theme' => ['nullable', Rule::in(self::ALLOWED_THEMES)],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        $theme = isset($input['theme']) && in_array($input['theme'], self::ALLOWED_THEMES, true)
            ? $input['theme']
            : ($user->theme ?? 'blue-theme');

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input, $theme);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'theme' => $theme,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input, string $theme): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'theme' => $theme,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
