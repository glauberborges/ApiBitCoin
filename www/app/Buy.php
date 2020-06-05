<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buy extends Model {

    protected $table = "buy";

    protected $fillable = [
        "value",
        "rate_sell",
        "created_at",
        "updated_at",
    ];

    protected $hidden = [
        "user_id"
    ];
}
