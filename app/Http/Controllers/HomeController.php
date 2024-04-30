<?php

namespace App\Http\Controllers;

use App\Models\Totp;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function show()
    {
        return Inertia::render('MyFirstWelcomePage', [
            'info' => Totp::getAllInfo()
          ]);
    }
}
