<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model {

    protected $table = "purchase_order";

    protected $fillable = [
        "sale_amount",
        "desired_value",
        "created_at",
        "updated_at",
    ];

    protected $hidden = [
        "user_id"
    ];

    public function desired_value($price_btc) {
        return PurchaseOrder::where("desired_value", "<=", $price_btc)
            ->where('status', 'PENDING')
            ->get();
    }
}
