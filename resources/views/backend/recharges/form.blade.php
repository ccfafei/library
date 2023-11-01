
{{ csrf_field() }}
<input type="hidden" id="form-id" name="id">

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">用户姓名：</label>
    <div class="col-sm-2">
        <div class="input-group">
            <p id="b_name" class="form-control-static"></p>
            <input type="hidden" id="form-user_id" name="user_id">
        </div>

    </div>
    <label class="col-sm-3 control-label">手机号：</label>
    <div class="col-sm-2">
        <div class="input-group">
            <p id="b_phone" class="form-control-static"> </p>
        </div>
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">当前余额：</label>
    <div class="col-sm-2">
        <div class="input-group m-b">
            <span class="input-group-addon">¥</span>
            <span id="last_balance" class="input-group-addon"> </span>
            <input type="hidden" id ="form-last_balance" name="last_balance"/>
        </div>
    </div>

</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">充值金额：</label>
    <div class="col-sm-8">
        <div class="input-group m-b"><span class="input-group-addon">¥</span>
            <input id="amount" name="amount" type="number" class="form-control" style="text-align: right">
            <span class="input-group-addon">.00元</span>
        </div>
    </div>
</div>



<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">操作人：</label>
    <div class="col-sm-8">
        <p class="form-control-static" id="form-admin_name"></p>
            <input type="hidden" id="form-admin_id" name="admin_id">
    </div>
</div>

