<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
//use App\Models\Kids;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements AuthenticatableContract
{
    use Notifiable,Authenticatable;
    use HasRoles;

    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;


    /**
     * 查询小朋友信息
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kids()
    {
        return $this->hasMany(Kids::class,'p_id','id');
    }

    /**
     * 查询状态
     * @param $field
     * @return null|string
     */
    public static function isStatus($field){
        $data = self::where($field)->first();
        if ($data){
            if ($data->status == 0){
                return 'disable';
            }else if ($data->status == 1){
                return 'enable';
            }
        }
        return null;
    }

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
