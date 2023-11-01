
{{ csrf_field() }}
<input type="hidden" id="form-id" name="id">


<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">标题</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="title" id="form-title" required>
    </div>
</div>



<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">图片<small class="text-danger" style="font-weight: normal">(小于2M,尺寸:375*170)</small></label>
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
    <label class="col-sm-3 control-label">顺序</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="sort" id="form-sort" required>
    </div>
</div>

<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">链接地址</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="link_url" id="form-link_url" required>
    </div>
</div>


