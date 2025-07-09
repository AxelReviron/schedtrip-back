<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    function change(Request $request): \Illuminate\Http\RedirectResponse
    {
        request()->validate(['locale' => 'required|string']);
        session(['locale' => request('locale')]);
        return redirect()->back();
    }
}
