<?php

use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use App\Models\School;

$admins =     Auth::guard('admin')->user();
$stores =[];
$result = School::select('id','name')->where('status',1)->get();
if(collect($result)->isNotEmpty()){
    $stores = $result->toArray();
}

?>

<input type="hidden" id="form-id" name="id">
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">用户姓名：</label>
    <div class="col-sm-2">
        <div class="input-group">
            <input type="hidden" id="form_user_id" value=""/>
            <p id="form_name" class="form-control-static"></p>
        </div>

    </div>
    <label class="col-sm-3 control-label">手机号：</label>
    <div class="col-sm-2">
        <div class="input-group">
            <p id="form_phone" class="form-control-static"></p>
        </div>
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">当前余额：</label>
    <div class="col-sm-2">
        <div class="input-group m-b">
            <span class="input-group-addon">¥</span>
            <p id="form_balance" class="input-group-addon"></p>
        </div>
    </div>

</div>

<div class="table-responsive m-t">
    <table class="table invoice-table">
        <thead>
        <tr>
            <th>清单</th>
            <th>数量</th>
            <th>单价</th>
            <th>总价</th>
        </tr>
        </thead>
        <tbody id="costsinfo" onclick="acount();">

        </tbody>
    </table>
    <table class="table invoice-table">
        <thead>
        <tr>
            <th>消费名称</th>
            <th>消费金额</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <div class="input-group m-b">
                    <input id="remark" type="text" class="form-control" style="text-align: right">
                </div>
            </td>




            <td>
                <div class="input-group m-b">
                    <span class="input-group-addon">¥</span>
                    <input id="money" type="number" min="0"  value="0" class="form-control text-right">
                    <span class="input-group-addon">.00</span>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<table class="table invoice-total">
    <tbody>
    <tr>
        <td><strong>总计消费：</strong>
        </td>
        <td id="moneycount">¥ 0.00</td>
    </tr>
    <tr>
        <td><strong>操作人：</strong>
        </td>
        <td id="adminname" style="width: 25%">{{$admins->name}}</td>
    </tr>
    </tbody>
</table>
