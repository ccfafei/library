<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{

    protected $table = 'costs';

    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;

    //用户关联
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    //操作员关联
    public function admin()
    {
        return $this->belongsTo(Admin::class,'admin_id','id');
    }

    //店铺关联
    public function store()
    {
        return $this->belongsTo(School::class,'store_id','id');
    }

    //消费的项目明细关联
    public function details()
    {
        return $this->hasMany(CostDetail::class,'cost_id','id');

    }
}
