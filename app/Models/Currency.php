<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'symbol',
        'exchange_rate',
        'is_default'
    ];
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
