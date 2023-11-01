
<!DOCTYPE HTML>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <title>条形码生成</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="Description" content="条形码生成">

    <link href="{{asset('backend/JsBarcode/font-awesome.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('backend/JsBarcode/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/JsBarcode/bootstrap-theme.min.css')}}">
    <link rel="stylesheet" type='text/css' href="{{asset('backend/JsBarcode/rangeslider.css')}}">
    <link rel="stylesheet" type='text/css' href="{{asset('backend/JsBarcode/style.css')}}">

    <script src="{{asset('backend/JsBarcode/jquery-2.1.3.min.js')}}"></script>
    <script src="{{asset('backend/JsBarcode/JsBarcode.all.min.js')}}"></script>
    <script src="{{asset('backend/JsBarcode/bootstrap.min.cssrangeslider.min.js')}}"></script>
    <script src="{{asset('backend/JsBarcode/jqColorPicker.min.js')}}"></script>
    <script src="{{asset('backend/JsBarcode/script.js')}}"></script>
    <script src="{{asset('backend/JsBarcode/bootstrap.min.js')}}"></script>
  </head>
  <body>
    <div id="main">
      <div class="barcode-container">
        <svg id="barcode"></svg>
      </div>
      <div class="container">
        <div class="row search-bar">
          <div class="col-md-10 col-md-offset-1">
            <div class="input-group margin-bottom-sm">
              <span class="input-group-addon"><i class="fa fa-barcode fa-fw"></i></span>
              <input class="form-control" id="userInput" type="text" value="123456789" placeholder="Barcode" autofocus>
              <span class="input-group-btn">
                <select class="btn barcode-select" id="barcodeType" title="CODE128">
                  <option value="CODE128">CODE128 auto</option>
                  <option value="CODE128A">CODE128 A</option>
                  <option value="CODE128B">CODE128 B</option>
                  <option value="CODE128C">CODE128 C</option>
                  <option value="EAN13">EAN13</option>
                  <option value="EAN8">EAN8</option>
                  <option value="UPC">UPC</option>
                  <option value="CODE39">CODE39</option>
                  <option value="ITF14">ITF14</option>
                  <option value="ITF">ITF</option>
                  <option value="MSI">MSI</option>
                  <option value="MSI10">MSI10</option>
                  <option value="MSI11">MSI11</option>
                  <option value="MSI1010">MSI1010</option>
                  <option value="MSI1110">MSI1110</option>
                  <option value="pharmacode">Pharmacode</option>
                </select>
              </span>
            </div>
          </div>
        </div>
        <!-- Bar width -->
        <div class="row">
          <div class="col-md-2 col-xs-12 col-md-offset-1 description-text"><p>长度</p></div>
          <div class="col-md-7 col-xs-11 slider-container"><input id="bar-width" type="range" min="1" max="4" step="1" value="2"/></div>
          <div class="col-md-1 col-xs-1 value-text"><p><span id="bar-width-display"></span></p></div>
        </div>
        <!-- Height -->
        <div class="row">
          <div class="col-md-2 col-xs-12 col-md-offset-1 description-text"><p>高度</p></div>
          <div class="col-md-7 col-xs-11 slider-container"><input id="bar-height" type="range" min="10" max="150" step="5" value="100"/></div>
          <div class="col-md-1 col-xs-1 value-text"><p><span id="bar-height-display"></span></p></div>
        </div>
        <!-- Margin -->
        <div class="row" style="display:none;">
          <div class="col-md-2 col-xs-12 col-md-offset-1 description-text"><p>边距</p></div>
          <div class="col-md-7 col-xs-11 slider-container"><input id="bar-margin" type="range" min="0" max="25" step="1" value="10"/></div>
          <div class="col-md-1 col-xs-1 value-text"><p><span id="bar-margin-display"></span></p></div>
        </div>
        <!-- Background (color) -->
        <div class="row">
          <div class="col-md-2 col-xs-12 col-md-offset-1 description-text"><p>背景</p></div>
          <div class="col-md-7 col-xs-11 input-container"><input id="background-color" class="form-control color" value="#FFFFFF"/></div>
          <div class="col-md-1 col-xs-1 value-text"></div>
        </div>
        <!-- Line color -->
        <div class="row">
          <div class="col-md-2 col-xs-12 col-md-offset-1 description-text"><p>颜色</p></div>
          <div class="col-md-7 col-xs-11 input-container"><input id="line-color" class="form-control color" value="#000000"/></div>
          <div class="col-md-1 col-xs-1 value-text"></div>
        </div>
        <!-- Show text -->
        <div class="row checkbox-options">
          <div class="col-md-2 col-md-offset-1 col-xs-12 col-xs-offset-0 description-text"><p>文字显示</p></div>
          <div class="col-md-7 col-xs-12 center-text">
            <div class="btn-group btn-group-md" role="toolbar">
              <button type="button" class="btn btn-default btn-primary display-text" value="true">显示</button>
              <button type="button" class="btn btn-default display-text" value="false">隐藏</button>
            </div>
          </div>
          <div class="col-md-1 col-xs-0"></div>
        </div>
        <div id="font-options">
          <!-- Text align -->
          <div class="row checkbox-options">
            <div class="col-md-2 col-md-offset-1 col-xs-12 col-xs-offset-0 description-text"><p>文字对齐</p></div>
            <div class="col-md-7 center-text">
              <div class="btn-group btn-group-md" role="toolbar">
                <button type="button" class="btn btn-default text-align" value="left">靠左</button>
                <button type="button" class="btn btn-default btn-primary text-align" value="center">居中</button>
                <button type="button" class="btn btn-default text-align" value="right">靠右</button>
              </div>
            </div>
            <div class="col-md-1"></div>
          </div>
          <!-- Font -->
          <div class="row">
            <div class="col-md-2 col-md-offset-1 col-xs-12 col-xs-offset-0 description-text"><p>字体</p></div>
            <div class="col-md-7 center-text">
              <select class="form-control" id="font" style="font-family: monospace">
                <option value="monospace" style="font-family: monospace" selected="selected">Monospace</option>
                <option value="sans-serif" style="font-family: sans-serif">Sans-serif</option>
                <option value="serif" style="font-family: serif">Serif</option>
                <option value="fantasy" style="font-family: fantasy">Fantasy</option>
                <option value="cursive" style="font-family: cursive">Cursive</option>
              </select>
            </div>
            <div class="col-md-1"></div>
          </div>
          <!-- Font options -->
          <div class="row checkbox-options">
            <div class="col-md-2 col-md-offset-1 col-xs-12 col-xs-offset-0 description-text"><p>字体样式</p></div>
            <div class="col-md-7 center-text">
              <div class="btn-group btn-group-md" role="toolbar">
                <button type="button" class="btn btn-default font-option" value="bold" style="font-weight: bold">加粗</button>
                <button type="button" class="btn btn-default font-option" value="italic" style="font-style: italic">经典</button>
              </div>
            </div>
            <div class="col-md-1"></div>
          </div>
          <!-- Font size -->
          <div class="row">
            <div class="col-md-2 col-md-offset-1 col-xs-12 col-xs-offset-0 description-text"><p>字体大小</p></div>
            <div class="col-md-7 col-xs-11 slider-container"><input id="bar-fontSize" type="range" min="8" max="36" step="1" value="20"/></div>
            <div class="col-md-1 col-xs-1 value-text"><p><span id="bar-fontSize-display"></span></p></div>
          </div>
          <!-- Text margin -->
          <div class="row">
            <div class="col-md-2 col-md-offset-1 col-xs-12 col-xs-offset-0 description-text"><p>文字边距</p></div>
            <div class="col-md-7 col-xs-11 slider-container"><input id="bar-text-margin" type="range" min="-15" max="40" step="1" value="0"/></div>
            <div class="col-md-1 col-xs-1 col-xs-11 value-text"><p><span id="bar-text-margin-display"></span></p></div>
          </div>
        </div>

      </div>

      <div class="row">
        <input type="button" class="btn btn-default btn-danger" value="下载" style="margin-right: 25px;" id="download">
        <input type="button" class="btn btn-default btn-primary" value="打印" id="printer">
      </div>

    </div>
    <script src="{{asset('backend/JsBarcode/print.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('backend/JsBarcode/print.min.css')}}">
   <script>
       $(function() {
           $("#download").bind("click",function(){
               var svgXml = $('.barcode-container').html();
               var image = new Image;
               //image.src = "data:image/svg+xml;charset=utf-8," + encodeURIComponent(svgXml);
               image.src = 'data:image/svg+xml;base64,' + window.btoa(unescape(encodeURIComponent(svgXml))); //给图片对象写入base64编码的svg流
               var canvas = document.createElement("canvas");
               canvas.width = $('#barcode').width();
               canvas.height = $('#barcode').height();
               var context = canvas.getContext("2d");
               context.fillStyle = '#fff';//#fff设置保存后的PNG 是白色的
               context.fillRect(0, 0, 10000, 10000);
               image.onload = function() {
                   context.drawImage(image, 0, 0);
                   var a = document.createElement("a");
                   a.download = "barcode";
                   a.href = canvas.toDataURL("image/png");
                   a.click();
               };
           });

           $("#printer").bind("click",function(){
               var svgXml = $('.barcode-container').html();
               var image = new Image;
               //image.src = "data:image/svg+xml;charset=utf-8," + encodeURIComponent(svgXml);
               image.src = 'data:image/svg+xml;base64,' + window.btoa(unescape(encodeURIComponent(svgXml))); //给图片对象写入base64编码的svg流
               var canvas = document.createElement("canvas");
               canvas.width = $('#barcode').width();
               canvas.height = $('#barcode').height();
               var context = canvas.getContext("2d");
               context.fillStyle = '#fff';//#fff设置保存后的PNG 是白色的
               // context.fillRect(0, 0, 10000, 10000);
               context.fillRect(0, 0, 10000, 10000);
               image.onload = function() {
                   context.drawImage(image, 0, 0);
                   var imgurl = canvas.toDataURL();
                   printJS({printable: imgurl, type: 'image'})
               };

           });


       });






  </script>
  </body>
</html>
