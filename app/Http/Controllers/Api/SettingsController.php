<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * GET /api/v1/settings — identity, contact, support and social data for
     * any headless frontend. Unset keys return null so the contract stays
     * forward-compatible as new fields are added in the admin.
     */
    public function show()
    {
        $s = Setting::allCached();
        $media = fn (?string $path) => $path ? url(Storage::disk('public')->url($path)) : null;

        return response()->json([
            'name' => $s['site_name'] ?? config('site.name'),
            'short_name' => $s['club_short_name'] ?? config('site.short_name'),
            'tagline' => $s['tagline'] ?? config('site.tagline') ?: null,
            'founded_year' => $s['founded_year'] ?? null,
            'city' => $s['city'] ?? null,
            'stadium' => $s['stadium'] ?? null,
            'season' => $s['season'] ?? null,
            'league_name' => $s['league_name'] ?? null,
            'logo_url' => $media($s['logo'] ?? null) ?? url('/images/logo.png'),
            'hero' => [
                'kicker' => $s['hero_kicker'] ?? null,
                'title' => $s['hero_title'] ?? null,
                'subtitle' => $s['hero_subtitle'] ?? null,
                'image_url' => $media($s['hero_image'] ?? null),
            ],
            'youth' => [
                'intro' => $s['youth_intro'] ?? null,
                'age_range' => $s['youth_age_range'] ?? null,
                'training_info' => $s['youth_training_info'] ?? null,
                'phone' => $s['youth_phone'] ?? null,
                'email' => $s['youth_email'] ?? null,
            ],
            'support' => [
                'intro' => $s['support_intro'] ?? null,
                'account_holder' => $s['account_holder'] ?? null,
                'bank_name' => $s['bank_name'] ?? null,
                'bank_account' => $s['bank_account'] ?? null,
                'payment_purpose' => $s['payment_purpose'] ?? null,
                'payment_code' => $s['payment_code'] ?? null,
                'payment_model' => $s['payment_model'] ?? null,
                'payment_reference' => $s['payment_reference'] ?? null,
                'iban' => $s['iban'] ?? null,
                'swift' => $s['swift'] ?? null,
                'note' => $s['support_note'] ?? null,
            ],
            'contact' => [
                'email' => $s['email'] ?? null,
                'phone' => $s['phone'] ?? null,
                'address' => $s['address'] ?? null,
                'hours' => $s['working_hours'] ?? null,
            ],
            'socials' => [
                'facebook' => $s['facebook'] ?? null,
                'instagram' => $s['instagram'] ?? null,
                'youtube' => $s['youtube'] ?? null,
                'tiktok' => $s['tiktok'] ?? null,
            ],
            'maps_embed' => $s['map_embed'] ?? null,
            'seo' => [
                'title' => $s['seo_title'] ?? null,
                'description' => $s['seo_description'] ?? null,
            ],
        ]);
    }
}
