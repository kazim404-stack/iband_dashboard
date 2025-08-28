<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = [
        "site_name",
        "logo",
        "facebook",
        "instagram",
        "whatsapp",
        "youtube",
        "telegram",
        "x",
        "linkedin",
    ];
    public function contacts(){
        return $this->hasMany(Contact::class);
    }
}
