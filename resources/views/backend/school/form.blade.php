
{{ csrf_field() }}
<input type="hidden" id="form-id" name="id">
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">名称</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="name" id="form-name" required>
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">地址</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="address" id="form-address" required>
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">园区负责人</label>
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
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">状态</label>
    <div class="col-sm-8">
        <select class="form-control" name="status" id="form-status">
            @foreach(config('params')['status'] as $key => $value)
                <option value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
    </div>
</div>

