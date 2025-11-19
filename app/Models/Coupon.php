<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Coupon extends Model
{
    //
    protected $fillable = ['name', 'discount', 'valid_until'];
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::upper($value);
    }
    public function checkIfValid()
    {
        if ($this->valid_until > Carbon::now()) {
            return true;
        } else {
            return false;
        }
    }
}
