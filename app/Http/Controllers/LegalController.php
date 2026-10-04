<?php

namespace App\Http\Controllers;

use App\Models\Setting;

/**
 * Privacy policy and terms of use. The wording lives in resources/views/legal/*
 * (one partial per document and language); the controller only hands them the
 * identity of the operator, taken from the Settings the club already maintains.
 */
class LegalController extends Controller
{
    /** Bump when the text of either document changes. */
    private const UPDATED = '2026-10-04';

    public function privacy()
    {
        return $this->render('privacy');
    }

    public function terms()
    {
        return $this->render('terms');
    }

    private function render(string $doc)
    {
        $operator = [
            'name' => Setting::get('legal_name') ?: (Setting::get('site_name') ?: config('app.name')),
            'id' => Setting::get('legal_id'),
            'address' => Setting::get('address'),
            'email' => Setting::get('email'),
            'phone' => Setting::get('phone'),
            'youth_email' => Setting::get('youth_email'),
        ];

        return view('legal.show', [
            'doc' => $doc,
            'operator' => $operator,
            'updated' => \Illuminate\Support\Carbon::parse(self::UPDATED),
        ]);
    }
}
