<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class ExtractController extends Controller {

    public function extract() {
        return response()->json( User::find(Auth::id())->extract, 200);
    }

    public function extract_period($start, $end) {
        return response()->json( User::find(Auth::id())->extract_period($start, $end), 200);
    }
}


