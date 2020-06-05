<?php

namespace App\Http\Controllers;

use App\Deposits;
use App\Extract;
use App\Mail\Mails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\JWTAuth;
use Validator;

class AccountController extends Controller {

    public function register(Request $request) {

        //validate
        $this->validate($request, [
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed',
        ]);

        try {
            $user = new User();
            $user->name             = $request->input('name');
            $user->email            = $request->input('email');
            $user->password         = app('hash')->make($request->input('password'));
            $user->balance          = 0;
            $user->btc_balance      = 0;

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'Created'], 200);

        } catch (\Exception $e) {
            //return error message
            return response()->json([
                'erro'      => 'erro',
                'message'   => 'User Registration Failed!',
            ], 409);
        }

    }

    public function login(Request $request) {

        $this->validate($request, [
            'email'         => 'required|string',
            'password'      => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized, incorrect username or password'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function deposit(Request $request) {

        $this->validate($request, [
            'amount'         => 'required|numeric',
        ]);

        $amount = $request->input('amount');

        User::find(Auth::id())->increment('balance',$request->input('amount'));

        $deposits = new Deposits();
        $deposits->value    = $amount;
        $deposits->user_id  = Auth::id();
        $deposits->save();

        $extract = new Extract();
        $extract->register([
            "type"         => "DEPOSIT",
            "amount_btc"   => 0,
            "amount"       => $amount,
            "description"  => "deposit on the platform",
            "user_id"      => Auth::id(),
        ]);

        $mail = new Mails(Auth::id());
        $mail->mail_balace($request->input('amount'));

        return response()->json(['message' =>  "Deposit successfully completed"], 200);
    }

    public function balance() {

        $balance = User::find(Auth::id(), ['balance']);

        return response()->json($balance, 200);
    }
}


