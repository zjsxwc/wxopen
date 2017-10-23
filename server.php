<?php
/**
 * Created by IntelliJ IDEA.
 * User: wangchao
 * Date: 10/23/17
 * Time: 12:21 PM
 */

$postJson = json_encode($_POST);
file_put_contents(__DIR__."/logs/pos.json", $postJson);

include __DIR__ . '/vendor/autoload.php'; // 引入 composer 入口文件
use EasyWeChat\Foundation\Application;
$options = [
    'debug'  => true,
    'app_id' => 'wx85b3b4769193943c',
    'secret' => 'A3c35618954c50d5744f1178cdf10e53',
    'token'  => 'wangchaowxopen',
    // 'aes_key' => null, // 可选
    'log' => [
        'level' => 'debug',
        'file'  => __DIR__.'/logs/easywechat.log', // XXX: 绝对路径！！！！
    ],
    //...
];
$app = new Application($options);


$app->server->setMessageHandler(function ($message) {
    return "您好！欢迎关注我!";
});


$response = $app->server->serve();
// 将响应输出
$response->send(); // Laravel 里请使用：return $response;
