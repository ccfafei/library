<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{

    protected $table = 'grades';

    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;

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

    /**
     * 所属园所
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(){
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

}
