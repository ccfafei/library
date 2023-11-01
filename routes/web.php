<?php

use Illuminate\Routing\Router;
Route::get('/',function (){
    return view('welcome');
});

Route::group([
    'prefix'        => 'backend',
    'namespace'     => 'Backend',
    'middleware'    => ['web'],
], function (Router $router) {

    $router->get('login', ['as' => 'backend.loginGet', 'uses' => 'PublicController@loginGet']);
    #$router->post('login', ['as' => 'backend.loginPost', 'uses' => 'PublicController@loginPost']);
    
    $router->get('logout', ['as' => 'backend.logout', 'uses' => 'PublicController@logout']);
    $router->post('upload/image', ['as' => 'backend.uploadImg', 'uses' => 'UploadController@image']);

    $router->get('index', ['as' => 'backend.index', 'uses' => 'IndexController@index']);
    $router->get('base', ['as' => 'backend.base', 'uses' => 'IndexController@base']);

    $router->get('admin', ['as' => 'backend.admin.index', 'uses' => 'AdminController@index']);
    $router->post('admin', ['as' => 'backend.admin.save', 'uses' => 'AdminController@save']);

    //园所信息
    $router->get('school', ['as' => 'backend.school.index', 'uses' => 'SchoolController@index']);
    $router->post('school', ['as' => 'backend.school.save', 'uses' => 'SchoolController@save']);
    $router->post('school/del', ['as' => 'backend.school.del', 'uses' => 'SchoolController@destory']);

    //班级信息
    $router->get('grade', ['as' => 'backend.grade.index', 'uses' => 'GradeController@index']);
    $router->post('grade', ['as' => 'backend.grade.save', 'uses' => 'GradeController@save']);
    $router->post('grade/del', ['as' => 'backend.grade.del', 'uses' => 'GradeController@destory']);

    //用户信息
    $router->get('customer', ['as' => 'backend.customer.index', 'uses' => 'UserController@customer']);
    $router->get('barber', ['as' => 'backend.barber.index', 'uses' => 'UserController@barber']);
    $router->post('user', ['as' => 'backend.user.save', 'uses' => 'UserController@save']);
    $router->get('customer/borrow', ['as' => 'backend.customer.borrow', 'uses' => 'UserController@borrow']);

    //小朋友信息
    $router->get('kid', ['as' => 'backend.kid.index', 'uses' => 'KidController@index']);
    $router->post('kid', ['as' => 'backend.kid.save', 'uses' => 'KidController@save']);

    //微信会员卡
    $router->get('vipcard/buy', ['as' => 'backend.vipcard.buy', 'uses' => 'VipCardController@buy']);
    $router->get('vipcard', ['as' => 'backend.vipcard.index', 'uses' => 'VipCardController@index']);
    $router->post('vipcard', ['as' => 'backend.vipcard.save', 'uses' => 'VipCardController@save']);
    $router->post('vipcard/del', ['as' => 'backend.vipcard.del', 'uses' => 'VipCardController@destory']);

    //充值
    $router->get('recharges', ['as' => 'backend.recharges.index', 'uses' => 'RechargesController@index']);
    $router->post('recharges', ['as' => 'backend.recharges.save', 'uses' => 'RechargesController@save']);

    //消费
    $router->get('costs', ['as' => 'backend.costs.index', 'uses' => 'CostsController@index']);
    $router->post('costsearch', ['as' => 'backend.costs.search', 'uses' => 'CostsController@index']);
    $router->post('costs', ['as' => 'backend.costs.save', 'uses' => 'CostsController@saveCost']);
    $router->get('getServers', ['as' => 'backend.costs.getServers', 'uses' => 'CostsController@getServers']);

    //借阅信息
    $router->get('borrow', ['as' => 'backend.borrow.index', 'uses' => 'BorrowController@index']);
    $router->post('borrow', ['as' => 'backend.borrow.save', 'uses' => 'BorrowController@save']);
    $router->post('borrow/del', ['as' => 'backend.borrow.del', 'uses' => 'BorrowController@destory']);
    $router->get('apply', ['as' => 'backend.apply.index', 'uses' => 'ApplyController@index']);
    $router->get('apply/list', ['as' => 'backend.apply.list', 'uses' => 'ApplyController@getListByStatus']);
    $router->post('apply', ['as' => 'backend.apply.save', 'uses' => 'ApplyController@save']);
    $router->post('apply/check', ['as' => 'backend.apply.check', 'uses' => 'ApplyController@checkOption']);
    $router->get('apply/exportExcel', ['as' => 'backend.apply.exportExcel', 'uses' => 'ApplyController@exportExcel']);

    //订单
    $router->get('order', ['as' => 'backend.order.index', 'uses' => 'OrderController@index']);
    $router->post('order', ['as' => 'backend.order.save', 'uses' => 'OrderController@save']);

    //书籍

    $router->get('book', ['as' => 'backend.book.index', 'uses' => 'BookController@index']);
    $router->post('book', ['as' => 'backend.book.save', 'uses' => 'BookController@save']);
    $router->post('book/del', ['as' => 'backend.book.del', 'uses' => 'BookController@destory']);
    $router->get('stocks', ['as' => 'backend.book.stocks', 'uses' => 'BookController@bookTotal']);
    $router->post('book/reading', ['as' => 'backend.book.reading', 'uses' => 'BookController@saveReading']);
    //$router->post('book/batchAdd', ['as' => 'backend.book.batchAdd', 'uses' => 'BookController@batchAdd']);
    $router->get('category', ['as' => 'backend.book_category.index', 'uses' => 'BookCategoryController@index']);
    $router->post('category', ['as' => 'backend.book_category.save', 'uses' => 'BookCategoryController@save']);
    $router->post('category/del', ['as' => 'backend.book_category.del', 'uses' => 'BookCategoryController@destory']);
    $router->get('barcode', ['as' => 'backend.barcode.index', 'uses' => 'BarcodeController@index']);

    //banner设置
    $router->get('banner', ['as' => 'backend.banner.index', 'uses' => 'BannerController@index']);
    $router->post('banner', ['as' => 'backend.banner.save', 'uses' => 'BannerController@save']);
    $router->post('banner/del', ['as' => 'backend.banner.del', 'uses' => 'BannerController@destory']);


});

Route::group([
    'prefix'        => 'index',
    'namespace'     => 'Index',
    'middleware'    => ['web'],
], function (Router $router) {
    $router->get('index', ['as' => 'index.index', 'uses' => 'IndexController@index']);
    $router->get('base', ['as' => 'index.base', 'uses' => 'IndexController@base']);

    $router->get('login', ['as' => 'index.loginGet', 'uses' => 'PublicController@loginGet']);
    #$router->post('login', ['as' => 'index.loginPost', 'uses' => 'PublicController@loginPost']);
    $router->get('logout', ['as' => 'index.logout', 'uses' => 'PublicController@logout']);
    $router->get('web_store/{id}', 'PublicController@school');
    $router->get('showstore/{id}', 'PublicController@showstore');
    $router->get('video', ['as' => 'index.video.video', 'uses' => 'PublicController@video']);
    $router->get('order', ['as' => 'index.order.index', 'uses' => 'OrderController@index']);
    $router->get('order/image/{id}', ['as' => 'index.order.image', 'uses' => 'OrderController@image']);
    $router->post('order/image', ['as' => 'index.order.uploadImage', 'uses' => 'OrderController@uploadImage']);


});


Route::group([
    'middleware'    => ['api'],
], function (Router $router) {
    $router->post('backend/login', ['as' => 'backend.loginPost', 'uses' => 'Backend\PublicController@loginPost']);

    $router->post('index/login', ['as' => 'index.loginPost', 'uses' => 'Index\PublicController@loginPost']);
});
