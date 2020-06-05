<?php namespace App\Console\Commands;

use App\Extract;
use App\Mail\Mails;
use App\PurchaseOrder;
use App\Tools\Bitcoins;
use App\User;
use Illuminate\Console\Command;

class SalesOrderCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'salesOrder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sell as order when you reach the desired value';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $purchase_order = new PurchaseOrder();

        $orders = $purchase_order->desired_value(Bitcoins::rates()->sell);

        foreach ($orders as $order) {
            $user = User::find($order->user_id);

            // partial sale of bitcoin
            if($order->sale_amount < $user->btc_balance){
                $btc_residual =  $user->btc_balance - $order->sale_amount;

                $user->balance          = $user->balance + Bitcoins::btc_real($user->btc_balance);
                $user->btc_balance      = 0;
                $user->save();

                $extract = new Extract();
                $extract->register([
                        "type"         => "SALES",
                        "amount_btc"   => $order->sale_amount,
                        "amount"       => Bitcoins::btc_real($order->sale_amount),
                        "description"  => "settlement of the investment",
                        "user_id"      => $order->user_id,
                ]);

                $user->btc_balance      = $btc_residual;
                $user->balance          = $user->balance - Bitcoins::btc_real($btc_residual);
                $user->save();

                $extract = new Extract();
                $extract->register([
                    "type"         => "BUY",
                    "amount_btc"   => $btc_residual,
                    "amount"       => Bitcoins::btc_real($btc_residual),
                    "description"  => "buy residual bitcoin",
                    "user_id"      => $order->user_id,
                ]);
            }else{
                // total bitcoin sale
                $user->btc_balance      = $user->btc_balance - $order->sale_amount;
                $user->balance          = $user->balance + Bitcoins::btc_real($order->sale_amount);
                $user->save();
            }

            $purchaseorder_edit = PurchaseOrder::find($order->id);
            $purchaseorder_edit->status = "PROCESSED";
            $purchaseorder_edit->save();

            $mail = new Mails($order->user_id);
            $mail->mail_sales(Bitcoins::btc_real($order->sale_amount), $order->sale_amount);
        }

        return true;

    }

}
