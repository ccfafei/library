<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{

    protected $table = 'banner';

    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;

}
