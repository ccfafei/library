<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kids extends Model
{

    protected $table = 'kids';
    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;


    //获取家长信息
    public function user()
    {
        return $this->belongsTo(User::class,'p_id','id');
    }

    //获取园所信息
    public function school()
    {
        return $this->belongsTo(School::class,'school_id','id');
    }


    //获取班级信息
    public function grade()
    {
        return $this->belongsTo(Grade::class,'grade_id','id');
    }
}
