
{{ csrf_field() }}
<input type="hidden" id="form-id" name="id">

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">条码编号</label>
    <div class="col-sm-8">
        {{--<input type="text" class="form-control" name="book_sn" id="form-book_sn" required>--}}
        <textarea  rows="5" cols="20" class="form-control" name="book_sn" id="form-book_sn"></textarea>
        <small  class="text-danger">添加时可批量扫码，每本书是唯一的，修改时只能填写一个.</small>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">ISBN</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="ISBN" id="form-ISBN" required>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">名称</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="name" id="form-name" required>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">作者</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="author" id="form-author" required>
    </div>
</div>



<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">出版社</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="publisher" id="form-publisher" required>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">价格</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="price" id="form-price" required>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">材质</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="texture" id="form-texture" required>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">图书类别</label>
    <div class="col-sm-8">
        <select class="form-control" name="category_id" id="form-category_id" required>
            @foreach($book_category as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">图片<small class="text-danger" style="font-weight: normal">(大小不要超过2M)</small></label>
    <div class="col-sm-8">
        @include('backend.layout.upload', [
        'modelName' => 'upload_url',
        'modelIdName' => 'upload_id',
        'inputId' => 'one',
        'actionCtrl' => 'uploadImg',
        'uploadPath' => 'book_img',
        ])
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">简介</label>
    <div class="col-sm-8">
        <textarea rows="3" cols="20" id="form-remark" class="form-control" name="remark"></textarea>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">状态</label>
    <div class="col-sm-8">
        <select class="form-control" name="status" id="form-status">
            @foreach(config('params')['book_status'] as $key => $value)
                <option value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
    </div>
</div>

