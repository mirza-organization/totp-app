<?php

namespace App\Http\Controllers;

use App\Models\Totp;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function show()
    {
        // $info = Totp::getAllInfo();

        // return Inertia::render('MyFirstWelcomePage', [
        //     'info' => $info
        // ]);

        return Inertia::render('MyFirstWelcomePage');
    }

    public function getInfo(Request $request)
    {
        // dd($request->page);
        return response()->json(Totp::getAllInfo());
    }
}
