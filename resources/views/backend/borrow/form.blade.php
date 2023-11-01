
{{ csrf_field() }}
<input type="hidden" id="form-id" name="id">
<div class="form-group form-group-sm">

    <label class="col-sm-3 control-label">图书编号</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="book_sn" id="form-book_sn" required>
    </div>
    <label class="col-sm-3 control-label">图书名称</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="book_name" id="form-book_name">(可不填)
    </div>

</div>
<div class="form-group form-group-sm">

    <label class="col-sm-3 control-label">家长电话</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="phone" id="form-phone" required>
    </div>

    <label class="col-sm-3 control-label">家长姓名</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="user_name" id="form-user_name">(可不填)
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">借阅状态</label>
    <div class="col-sm-8">
        <select class="form-control" name="status" id="form-status">
            <option value="0">已借</option>
            <option value="1">已还</option>
        </select>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">借阅时间</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="borrow_ts" id="form-borrow_ts" required>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">归还时间</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="back_ts" id="form-back_ts">
    </div>
</div>



