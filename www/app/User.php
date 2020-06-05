<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject {

    use Authenticatable, Authorizable;

    protected $fillable = [
        'name',
        'email',
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function buy() {
        return $this->hasMany(Buy::class);
    }

    public function extract() {
        $nineten_days = date("Y-m-d H:m:i", strtotime("+90 days"));
        return $this->hasMany(Extract::class)->whereDate('created_at', '<=' ,$nineten_days);
    }

    public function extract_period($start, $end) {
        return $this->hasMany(Extract::class)->whereBetween('created_at', [$start, $end])->get();
    }
}
