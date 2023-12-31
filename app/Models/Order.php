<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = 'orders';

    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;


    public function customer(){
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function barber(){
        return $this->belongsTo(User::class, 'barber_id', 'id');
    }

    public function store(){
        return $this->belongsTo(School::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }



}
