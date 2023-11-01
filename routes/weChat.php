<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix'        => 'wechat',
    'namespace'     => 'WeChat',
    'middleware'    => ['api'],
], function (Router $router) {
    $router->any('/', 'PublicController@serve');
    $router->get('test', 'PublicController@test');

    $router->any('payment/vipCardNotify', 'PublicController@vipCardNotify');
    $router->any('payment/orderNotify', 'PublicController@orderNotify');

    //菜单
    $router->get('menu/list', 'MenuController@list');
    $router->get('menu/add', 'MenuController@add');

});

Route::group([
    'prefix'        => 'wechat',
    'namespace'     => 'WeChat',
    'middleware'    => ['web', 'wechat.oauth'],
//    'middleware'    => ['web'],
], function (Router $router) {

    //首页
    $router->get('index', 'BookController@getList');

    //搜索
    $router->get('search', 'SearchController@search');

    //图书
    $router->get('book/list', 'BookController@getList');
    $router->get('book/detail', 'BookController@getDetail');
    $router->get('book/more', 'BookController@more');
    $router->post('book/borrowCheck', 'BookController@borrowCheck'); //借书
    $router->post('book/checkCancel', 'BookController@checkCancel'); //取消借书
    $router->post('book/backCheck', 'BookController@backCheck'); //申请还书


    //会员信息展示与修改
    $router->post('getVCode', 'UserController@getVCode');
    $router->get('user', 'UserController@index');
    $router->get('user/bindphone', 'UserController@edit_phone');



    $router->get('user/info', 'UserController@memberinfo');
    $router->get('user/editinfo', 'UserController@editinfo');
    $router->post('user/save_info', 'UserController@saveMemberinfo');
    $router->get('user/getGrade', 'UserController@getGrade');

    $router->post('user/update_phone', 'UserController@update_phone');
    $router->get('user/vipcard', 'UserController@vipCard');
    $router->post('user/createTrade', 'UserController@createTrade');
    $router->post('user/postVipCard', 'UserController@postVipCard');


    //订单
    $router->get('order', 'OrderController@index');
    $router->get('order/{id}', 'OrderController@info');
    $router->post('order/add', 'OrderController@add');
    $router->post('order/serviceOk', 'OrderController@serviceOk');

    //借阅历史
    $router->get('history/index', 'HistoryController@index');
    $router->get('history/list', 'HistoryController@getListByStatus');

    //充值历史
    $router->get('history/recharges_list', 'HistoryController@getRechargesList');
    //消费历史
    $router->get('history/costs_list', 'HistoryController@getCostsList');


    //帮助
    $router->get('support/novice', 'SupportController@novice');
    $router->get('support/problem', 'SupportController@problem');
    $router->get('prompt','PromptController@index');


});

