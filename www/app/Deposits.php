<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposits extends Model {

    protected $table = "deposits";

    protected $fillable = [
        "value",
        "created_at",
        "updated_at",
    ];

    protected $hidden = [
        "user_id"
    ];
}
