<?php

namespace App\Http\Controllers;

use App\Models\Totp;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TwoFactorAuthController extends Controller
{
    public function show(Request $request)
    {
        return Inertia::render('2FACode', [
            'info' => Totp::getInfoById($request->uuid, ['*'])
        ]);
    }
}
