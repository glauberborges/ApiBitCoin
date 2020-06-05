<?php

namespace App\Http\Controllers;

use App\Buy;
use App\Extract;
use App\Mail\Mails;
use App\PurchaseOrder;
use App\Tools\Bitcoins;
use App\User;
use function array_push;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CryptoController extends Controller {

    public function price() {

        return response()->json([
            'buy'   => Bitcoins::rates()->buy,
            'sell'  => Bitcoins::rates()->sell
        ], 200);
    }

    public function purchase(Request $request) {

        $this->validate($request, [
            'amount'         => 'required|numeric',
        ]);

        $amount =  $request->input('amount');

        $user = User::find(Auth::id());

        if($user->balance <  $amount){
            return response()->json([
                'error'     =>  "Your balance is insufficient, make a new deposit.",
                'balance'   =>  $user->balance,
            ], 422);
        }

        $user->btc_balance      = $user->btc_balance + Bitcoins::real_btc_sell($amount);
        $user->balance          = $user->balance - $amount;
        $user->save();

        $buy = new Buy();
        $buy->value             = $amount;
        $buy->btc_purchased     = Bitcoins::real_btc_sell($amount);
        $buy->rate_sell         = Bitcoins::rates()->sell;
        $buy->user_id           = Auth::id();
        $buy->save();

        $extract = new Extract();
        $extract->register([
            "type"         => "BUY",
            "amount_btc"   => Bitcoins::real_btc_sell($amount),
            "amount"       => $amount,
            "description"  => "Bitcoin purchase",
            "user_id"      => Auth::id(),
        ]);

        $mail = new Mails(Auth::id());
        $mail->mail_purchase($amount,Bitcoins::real_btc_sell($amount));

        return response()->json([
            'message' =>  "Purchase successfully completed",
        ], 200);
    }

    public function position() {

        $user = User::find(Auth::id())->buy;

        $total_invested = 0;
        $investments    = [];
        foreach ($user as $row_user) {
            $total_invested += $row_user->value;
            array_push($investments, [
                "purchase_date"             => $row_user->created_at,
                "amount_invested"           => $row_user->value,
                "purchase_price"            => $row_user->rate_sell,
                "variation_percentage"      => Bitcoins::variation(),
            ]);
        }

        return response()->json([
            'investments'       =>  $investments,
            'total_invested'    =>  $total_invested,
        ], 200);
    }

    public function sales_order(Request $request) {

        $this->validate($request, [
            'amount'         => 'required|numeric',
            'desired_value'  => 'required|numeric|regex:/[\d].[\d]{2}/',
        ]);

        $amount         =  $request->input('amount');
        $desired_value  =  $request->input('desired_value');

        $user = User::find(Auth::id());

        if($amount > $user->btc_balance){
            return response()->json([
                'error'     =>  "Your balance is insufficient for this purchase.",
                'balance'   =>  $user->btc_balance,
            ], 422);
        }

        $purchase_order = new PurchaseOrder();
        $purchase_order->sale_amount        = $amount;
        $purchase_order->desired_value      = $desired_value;
        $purchase_order->status             = "PENDING";
        $purchase_order->user_id            = Auth::id();
        $purchase_order->save();

        return response()->json([
            'message' =>  "Sales order successfully registered.",
        ], 200);
    }

    public function volume() {
        return response()->json([
            'volume'   => Bitcoins::rates()->vol,
        ], 200);
    }

    public function history() {
        $date = new DateTime(date("Y-m-d"));

        $historic = [];
        foreach (Bitcoins::trades() as $item) {
            $date->setTimestamp($item->date);

            // 10 in 10 minutes
            if($date->format('i') % 10 == 0){
                $item->date = $date->format('Y-m-d H:i:s');
                array_push($historic, $item);
            }
        }

        return response()->json($historic, 200);
    }
}


