<?php

namespace App\Helpers\Classes\Traits;

use App\Helpers\Classes\Helper;
use App\Models\Setting;
use Illuminate\Support\Arr;

trait HasApiKeys
{
    public static function setPiAPIKey(): string
    {
        return Arr::random(explode(',', setting('piapi_ai_api_secret')));
    }

    public static function setFalAIKey(): string
    {
        $apiKeys = explode(',', setting('fal_ai_api_secret', 'empty'));

        return Arr::random($apiKeys);
    }

    // set klap api key
    public static function setKlapApiKey(): string
    {
        return setting('klap_api_key');
    }

    // set vizard api key
    public static function setVizardApiKey(): string
    {
        return setting('vizard_api_key', 'empty');
    }

    public static function setCreatifyAIKey(): array
    {
        config([
            'url-to-video.creatify_api_id'  => setting('creatify_api_id'),
            'url-to-video.creatify_api_key' => setting('creatify_api_key'),
        ]);

        return [
            'creatify_api_id'  => config('url-to-video.creatify_api_id', 'empty'),
            'creatify_api_key' => config('url-to-video.creatify_api_key', 'empty'),
        ];
    }

    public static function setTopviewKey(): array
    {
        $id = $key = '';
        if (! app()->runningInConsole()) {
            $id = setting('topview_api_id');
            $key = setting('topview_api_key');
        }

        config([
            'url-to-video.topview_api_id'  => $id,
            'url-to-video.topview_api_key' => $key,
        ]);

        return [
            'topview_api_id'  => config('url-to-video.topview_api_id', 'empty'),
            'topview_api_key' => config('url-to-video.topview_api_key', 'empty'),
        ];
    }

    public static function setAnthropicKey($setting = null): string
    {
        $settings = $setting ?? Setting::getCache();
        if ($settings?->getAttribute('user_api_option') || auth()->user()?->relationPlan?->getAttribute('user_api')) {
            $apiKeys = explode(',', auth()->user()?->getAttribute('anthropic_api_keys'));
        } else {
            $apiKeys = explode(',', setting('anthropic_api_secret'));
        }

        return Arr::random($apiKeys);
    }

    public static function setXAiKey($setting = null, $all = false): array|string|null
    {
        $settings = $setting ?? Setting::getCache();

        if ($settings?->getAttribute('user_api_option') || auth()->user()?->relationPlan?->getAttribute('user_api')) {
            $apiKeys = explode(',', auth()->user()?->getAttribute('xai_api_keys'));
        } else {
            $apiKeys = explode(',', setting('xai_api_secret'));
        }
        config(['xai.api_key' => $apiKeys[array_rand($apiKeys)]]);

        if ($all) {
            return $apiKeys;
        }

        return config('xai.api_key');
    }

    public static function setOpenAiKey($setting = null, $all = false): array|string|null
    {
        $settings = $setting ?? Setting::getCache();

        if (Helper::appIsNotDemo() && ($settings?->getAttribute('user_api_option') || auth()->user()?->relationPlan?->getAttribute('user_api'))) {
            $apiKeys = explode(',', auth()->user()?->getAttribute('api_keys'));
        } else {
            $apiKeys = explode(',', $settings?->getAttribute('openai_api_secret'));
        }
        config(['openai.api_key' => $apiKeys[array_rand($apiKeys)]]);

        if ($all) {
            return $apiKeys;
        }

        return config('openai.api_key');
    }

    public static function setGeminiKey($setting = null): string
    {
        $settings = $setting ?? Setting::getCache();
        if ($settings?->getAttribute('user_api_option') || auth()->user()?->relationPlan?->getAttribute('user_api')) {
            $apiKeys = explode(',', auth()->user()?->getAttribute('gemini_api_keys'));
        } else {
            $apiKeys = explode(',', setting('gemini_api_secret', ''));
        }
        config(['gemini.api_key' => $apiKeys[array_rand($apiKeys)]]);
        config(['gemini.request_timeout' => 120]);

        return config('gemini.api_key');
    }

    public static function setDeepseekKey($setting = null): string
    {
        $settings = $setting ?? Setting::getCache();
        if ($settings?->getAttribute('user_api_option') || auth()->user()?->relationPlan?->getAttribute('user_api')) {
            $apiKeys = explode(',', auth()->user()?->getAttribute('deepseek_api_secret'));
        } else {
            $apiKeys = explode(',', setting('deepseek_api_secret', ''));
        }
        config(['deepseek.api_key' => $apiKeys[array_rand($apiKeys)]]);
        config(['deepseek.request_timeout' => 120]);

        return config('deepseek.api_key');
    }
}
