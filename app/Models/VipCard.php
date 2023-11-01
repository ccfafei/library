<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VipCard extends Model
{

    protected $table = 'vip_card';

    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;

    public function school(){
        return $this->belongsTo(School::class,'school_id','id');
    }

}
