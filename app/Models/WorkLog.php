<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{

    protected $table = 'work_logs';

    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;

    public function barber(){
        return $this->belongsTo(User::class, 'barber_id', 'id');
    }

    public function store(){
        return $this->belongsTo(School::class);
    }

}
