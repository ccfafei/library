
{{ csrf_field() }}
<input type="hidden" id="form-id" name="id">
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">名称</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="name" id="form-name" required>
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">简介</label>
    <div class="col-sm-8">
        <textarea rows="3" cols="20" id="form-remark" class="form-control" name="remark"></textarea>
    </div>
</div>


