<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasTranslations;
    protected $fillable = [
        "general_setting_id",
        "email",
        "state",
        "address",
        "is_primary"
    ];
    public $translatable = ['address', 'state'];
    public function generalSetting(){
        return $this->belongsTo(GeneralSetting::class);
    }

}
