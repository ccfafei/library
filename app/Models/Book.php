<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Book extends Model
{

    protected $table = 'books';
    protected $guarded = [];
    protected $primaryKey = "id";
    protected $fields_all;


    /**
     * 查询书籍分类信息
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function category()
    {
        return $this->hasOne(BookCategory::class,'id','category_id');
    }



}
