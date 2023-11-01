<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{

    protected $table = 'apply';

    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;


    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }


    public function kid()
    {
        return $this->belongsTo(Kids::class,'kid_id','id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class,'book_id','id');
    }



    public function admin()
    {
        return $this->belongsTo(Admin::class,'admin_id','id');
    }

}
