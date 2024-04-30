<?php

namespace App\Http\Controllers;

use App\Models\Totp;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TotpController extends Controller
{
    public function search(Request $request)
    {
        try {
            // dd($request);
            $request->validate([
                'account_name' => 'required|string'
            ]);

            return response()->json([
                'data' => Totp::getAllInfo($request->account_name),
                'status' => true
            ]);
        } catch (Exception $error) {
            report($error);
            throw $error;
        }
    }

    public function edit(Request $request)
    {
        try {
            $request->validate([
                'uuid' => 'required|string',
                'account_name' => 'required|string',
            ]);

            Totp::updateInfo($request->uuid, $request->account_name);
        } catch (Exception $error) {
            report($error);
            throw $error;
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->validate([
                'uuid' => 'required|string'
            ]);

            Totp::deleteInfo($request->uuid);
        } catch (Exception $error) {
            report($error);
            throw $error;
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'account_name' => 'string',
                'secret' => 'required|string',
                'issuer' => 'required|string',
            ]);

            Totp::insertInfo($request->account_name, $request->secret, $request->issuer);
        } catch (Exception $error) {
            report($error);
            throw $error;
        }
    }
}
