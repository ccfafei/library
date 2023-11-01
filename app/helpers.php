<?php

use App\Models\User;
use App\Models\Kids;
use App\Models\School;
use App\Models\Grade;
/**
 * 根据用户id查询用户信息
 * @param $userid
 * @return array
 */
 function getUserInfos($userid){
    if(empty($userid)){
        return $userInfos=[];
    }
    try{
        $userInfos =  User::where(['status'=>1,'id'=>$userid])->first();
    }catch (\Exception $ex){
        $userInfos =[];
    }
    return $userInfos;
}

/**
 * 根据小孩id查询所在的班级
 * @param $child_id
 * @return array
 */
function getKidInfos($child_id){
    $kidInfos = [
        'id'=>'',
        'name'=>'',
        'school_id'=>'',
        'grade_id'=>'',
        'school_name'=>'',
        'grade_name'=>'',
    ];
    if(empty($child_id)){
        return $kidInfos;
    }
    try{
        $kidInfos =  Kids::where('id',$child_id)->first();
//        dd($child_id);die;
        if(!empty($kidInfos)){
            $kidInfos['school_name'] =  School::where('id',$kidInfos['school_id'])->value('name');
            $kidInfos['grade_name'] =  Grade::where('id',$kidInfos['grade_id'])->value('name');
        }
    }catch (\Exception $ex){

        return $kidInfos;
    }
    return $kidInfos;
}

/**
 * 截取中文字符串
 * @param $string 原有内容
 * @param $length 截取长度
 * @param bool $append 是否追加
 * @return bool|string
 */
function truncate($string,$length,$append = true){
    $string = trim($string);
    $strlength = strlen($string);
    if ($length == 0 || $length >= $strlength){
        return $string;
    }elseif ($length < 0){
        $length = $strlength + $length;
        if ($length < 0)
        {
            $length = $strlength;
        }
    }
    if (function_exists('mb_substr')){
        $newstr = mb_substr($string, 0, $length,"UTF-8");
    }elseif (function_exists('iconv_substr')){
        $newstr = iconv_substr($string, 0, $length,"UTF-8");
    }else{
        for($i=0;$i<$length;$i++){
            $tempstring=substr($string,0,1);
            if(ord($tempstring)>127){
                $i++;
                if($i<$length){
                    $newstring[]=substr($string,0,3);
                    $string=substr($string,3);
                }
            }else{
                $newstring[]=substr($string,0,1);
                $string=substr($string,1);
            }
        }
        $newstr =join($newstring);
    }
    if ($append && $string != $newstr){
        $newstr .= '...';
    }
    return $newstr;
}

/**
 * 根据状态获取颜色
 * @param $status
 * @return string
 */
function getTextColor($status){
    $color = "";
    switch ($status){
        case 0:
            $color = "text-success";
            break;
        case 1:
            $color = "text-danger";
            break;
        case 2:
            $color = "text-muted";
            break;
        default:
            $color = "";
            break;
    }
    return $color;
}

