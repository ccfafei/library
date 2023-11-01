<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VipCardLog extends Model
{

    protected $table = 'vip_card_logs';

    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
