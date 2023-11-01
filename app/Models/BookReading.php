<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReading extends Model
{

    protected $table = 'book_reading';

    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;
    public $timestamps = false;

    /**
     * 查询是否存在
     * @param $field
     * @param null $id
     * @return bool
     */
    public static function isExist($field, $id = null){
        $data = self::where($field)->first();
        if ($data){
            if ($id){
                if ($id != $data->id){
                    return true;
                }
            }else{
                return true;
            }
        }
        return false;
    }

}
