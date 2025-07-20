<?php

namespace App\Extensions\SocialMedia\System\Http\Controllers\Oauth;

use App\Extensions\SocialMedia\System\Enums\PlatformEnum;
use App\Extensions\SocialMedia\System\Helpers\Linkedin;
use App\Extensions\SocialMedia\System\Models\SocialMediaPlatform;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class LinkedinController extends Controller
{
    public function __construct(public Linkedin $linkedin) {}

    private function cacheKey(): string
    {
        return 'platforms.' . Auth::id() . '.linkedin';
    }

    public function redirect(Request $request): Application|Redirector|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if (Helper::appIsDemo()) {
            return back()->with([
                'type'    => 'error',
                'message' => trans('This feature is disabled in demo mode.'),
            ]);
        }

        if (setting('LINKEDIN_APP_ID') && setting('LINKEDIN_APP_SECRET')) {
            if ($request->has('platform_id') && $request->get('platform_id')) {
                cache()->remember($this->cacheKey(), 60, function () use ($request) {
                    return $request->get('platform_id');
                });
            }

            return $this->linkedin->authRedirect(
                config('social-media.linkedin.scopes', [])
            );
        }

        return back()->with([
            'type'    => 'error',
            'message' => 'Linkedin app id and secret not set. Please contact the administrator.',
        ]);
    }

    public function callback(Request $request): RedirectResponse
    {
        $code = $request->get('code');

        if (! $code) {
            return $this->redirectToPlatforms('error', 'Failed to get access token');
        }

        $getAccessTokenRes = $this->linkedin->getAccessToken($code);

        $tokenData = $getAccessTokenRes->json();

        $accessToken = $tokenData['access_token'] ?? null;

        $tokenExpireIn = $tokenData['expires_in'] ?? null;

        if ($getAccessTokenRes->failed() || ! $accessToken) {
            return $this->redirectToPlatforms('error', 'Failed to get access token');
        }

        $this->linkedin->setToken($accessToken);

        $getAccountInfoRes = $this->linkedin->getAccountInfo();

        if ($getAccountInfoRes->failed()) {
            return $this->redirectToPlatforms('error', 'Failed to get account info');
        }

        $userData = $getAccountInfoRes->json();

        $platformId = cache($this->cacheKey());

        if ($platformId && is_numeric($platformId)) {

            $platform = SocialMediaPlatform::query()
                ->where('id', $platformId)
                ->where('user_id', Auth::id())
                ->where('platform', PlatformEnum::linkedin->value)
                ->first();

            if ($platform) {
                $platform->update([
                    'credentials' => [
                        'platform_id' => $userData['sub'],
                        'name'        => $userData['name'] ?? '',
                        'username'    => $userData['email'] ?? '',
                        'picture'     => $userData['picture'],

                        'access_token'           => $accessToken,
                        'access_token_expire_at' => now()->seconds($tokenExpireIn),

                        'refresh_token'           => $accessToken,
                        'refresh_token_expire_at' => now()->seconds($tokenExpireIn),
                    ],
                    'connected_at' => now(),
                    'expires_at'   => now()->seconds($tokenExpireIn),
                ]);
            }

            cache()->forget($this->cacheKey());

        } else {
            SocialMediaPlatform::query()->create([
                'user_id'     => Auth::id(),
                'platform'    => PlatformEnum::linkedin->value,
                'credentials' => [
                    'platform_id' => $userData['sub'],
                    'name'        => $userData['name'] ?? '',
                    'username'    => $userData['email'] ?? '',
                    'picture'     => $userData['picture'],

                    'access_token'           => $accessToken,
                    'access_token_expire_at' => now()->seconds($tokenExpireIn),

                    'refresh_token'           => $accessToken,
                    'refresh_token_expire_at' => now()->seconds($tokenExpireIn),
                ],
                'connected_at' => now(),
                'expires_at'   => now()->seconds($tokenExpireIn),
            ]);
        }

        return $this->redirectToPlatforms('success', 'Linkedin account connected successfully.');
    }

    public function redirectToPlatforms(string $type = 'success', string $message = 'Linkedin account connected successfully.'): RedirectResponse
    {
        return to_route('dashboard.user.social-media.platforms')->with([
            'type'    => $type,
            'message' => trans($message),
        ]);
    }
}
