
{{ csrf_field() }}
<input type="hidden" id="form-id" name="id">
<input type="hidden" id="form-school_id" name="school_id">
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">班级名称</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="name" id="form-name" required>
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">班级负责人</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="leader" id="form-leader" required>
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">电话</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="tel" id="form-tel" required>
    </div>
</div>


