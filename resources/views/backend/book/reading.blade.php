
{{ csrf_field() }}

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">名称</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="name" id="form-name" readonly>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">ISBN</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="ISBN" id="form-ISBN" readonly>
    </div>
</div>


<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">阅读量</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="reading" id="form-reading" required>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">是否精选</label>
    <div class="col-sm-8">
        <label class="radio-inline">
            <input type="radio"  id="is_hot" value="1" name="is_hot">是
        </label>
        <label class="radio-inline">
            <input type="radio"  id="not_hot" value="0" name="is_hot">否
        </label>

    </div>
</div>

{{--<div class="form-group form-group-sm">--}}
    {{--<label class="col-sm-3 control-label">是否最新</label>--}}
    {{--<div class="col-sm-8">--}}
        {{--<label class="radio-inline">--}}
            {{--<input type="radio" id="is_new" value="1" name="is_new">是--}}
        {{--</label>--}}
        {{--<label class="radio-inline">--}}
            {{--<input type="radio"  id="not_new" value="0" name="is_new">否--}}
        {{--</label>--}}
    {{--</div>--}}
{{--</div>--}}

