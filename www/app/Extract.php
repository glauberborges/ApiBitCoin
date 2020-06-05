<?php

namespace App;

use App\Tools\Bitcoins;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Extract extends Model {

    protected $table = "extract";

    protected $fillable = [
        "type",
        "amount_btc",
        "amount",
        "rate_sell",
        "description",
        "created_at",
        "updated_at",

    ];

    protected $hidden = [
        "user_id",
    ];

    public function register($data = []) {
        $extract = new Extract();
        $extract->type          = Arr::get($data, 'type');
        $extract->amount_btc    = Arr::get($data, 'amount_btc');
        $extract->amount        = Arr::get($data, 'amount');
        $extract->rate_sell     = Bitcoins::rates()->sell;
        $extract->description   = Arr::get($data, 'description');
        $extract->user_id       = Arr::get($data, 'user_id');
        $extract->save();
    }
}
