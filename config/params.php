<?php

return [

    /*
    |--------------------------------------------------------------------------
    | params
    |--------------------------------------------------------------------------
    |
    */
    //客服电话
    'service_phone'=>'18327195504',

    // 每页显示条数
    'pageSize' => 10,

    // 状态
    'status' => [
        1 => '启用',
        0 => '禁用',
    ],


    'userType' => [
        0 => '消费者',
        1 => '在职人员',
    ],


    'uploadImg' => [
        'size' => 2, //上传图片大小，2M
//        'mimeType' => ['image/jpeg'],
    ],

    'pay_type' => [
        'wxPay' => '微信支付',
        'yearCard' => '年卡支付',
    ],


    // 订单状态
    'order_status' => [
        -1 => '未支付',
        1 => '待服务',
        2 => '待评价',
        3 => '已完成',
        4 => '已取消',
    ],

    // VIP类型
    'vip_type' => [
        0 => '体验卡',
        1 => '月卡',
        2 => '学期卡',
    ],


    // 提现申核类型
    'cash_checked' => [
        0 => '未审核',
        1 => '不通过',
        2 => '通过',
    ],

    //借阅状态
    'borrow_status'=>[
        0=>'待配送',
        1=>'取消借阅',
        2=>'完成配送',
        3=>'待回收',
        4=>'未完成',
        5=>'已完成',
    ],

    // 书籍出借状态
    'book_status' => [
        0 => '在馆',
        1 => '已借出',
        2 => '丢失',
        3 => '报废',
    ],

    //发短信操作分类
    'option_category'=>[
        1=>[
            'name'=>'取消借阅',
            'template_id'=>[
                'phone'=>'',
                'weixin'=>'',
            ],
        ],
        2=>[
            'name'=>'取消借阅',
            'template_id'=>[
                'phone'=>'',
                'weixin'=>'',
            ],
        ],
    ],

    //发送类型
    'send_type'=>[
        1=>'短信',
        2=>'微信',
        3=>'站内'
    ],

    //操作类型
    'option_type'=>[
        0=>'系统',
        1=>'人工'
    ],

    // 短信
    'sms' => [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,

        // 默认发送配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => [
                'yuntongxun',
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => '/tmp/easy-sms.log',
            ],
            'yuntongxun' => [
                'app_id' => env('YTX_APPID'),
                'account_sid' => env('YTX_SID'),
                'account_token' => env('YTX_TOKEN'),
                'is_sub_account' => false,
            ],
        ],
        //'vcode_template_id' => '333214',
        'vcode_template_id' => '474684',
        // 短信验证码有效期
        'cache_vcode_exp' => 10, // 10分钟
    ],

];
