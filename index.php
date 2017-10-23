<?php
/**
 * Created by IntelliJ IDEA.
 * User: wangchao
 * Date: 10/23/17
 * Time: 12:21 PM
 */

$postJson = json_encode($_POST);
file_put_contents(__DIR__."/logs/post.json", $postJson);

include __DIR__ . '/vendor/autoload.php'; // 引入 composer 入口文件
use EasyWeChat\Foundation\Application;
$options = [
    'debug'  => true,
    'app_id' => 'wx85b3b4769193943c',
    'secret' => 'a3c35618954c50d5744f1178cdf10e53',
    'token'  => 'wangchaowxopen',
    // 'aes_key' => null, // 可选
    'log' => [
        'level' => 'debug',
        'file'  => __DIR__.'/logs/easywechat.log', // XXX: 绝对路径！！！！
    ],
    //...
];
$app = new Application($options);
/** @var \EasyWeChat\User\User $userService */
$userService = $app->user;

$app->server->setMessageHandler(function ($message) use ($userService) {
    if ($message->MsgType == 'event') {
        if ($message->Event == 'subscribe') {
            return "您好！欢迎关注我!";
        }
    }

    if ($message->MsgType == 'text') {
        if ($message->Content == "我的信息") {
            $userOpenId = $message->FromUserName;
            $userInfo = $userService->get($userOpenId);
            return "你的信息 " . $userInfo['nickname'];
        }

        return "收到消息 " . $message->Content;
    }

    return "没有这种消息的处理方式";
});


$response = $app->server->serve();
// 将响应输出
$response->send(); // Laravel 里请使用：return $response;
