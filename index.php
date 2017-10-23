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
/** @var \EasyWeChat\Menu\Menu $menuService */
$menuService = $app->menu;

$app->server->setMessageHandler(function ($message) use ($userService, $menuService) {

    $buttons = [
        [
            "type" => "click",
            "name" => "按钮1",
            "key"  => "click-1"
        ],
        [
            "name"       => "菜单",
            "sub_button" => [
                [
                    "type" => "view",
                    "name" => "搜索",
                    "url"  => "http://www.soso.com/"
                ],
                [
                    "type" => "view",
                    "name" => "视频",
                    "url"  => "http://v.qq.com/"
                ],
                [
                    "type" => "click",
                    "name" => "你的信息",
                    "key"  => "click-user-info"
                ],
            ],
        ],
    ];



    /** @var \EasyWeChat\Support\Collection $message */
    if ($message->MsgType == 'event') {
        if ($message->Event == 'subscribe') {
            $menuService->add($buttons);
            return "您好！欢迎关注我!";
        }

        if  ($message->Event == 'CLICK') {
            if ($message->EventKey == 'click-1') {
                return "你点了按钮1";
            }
            if ($message->EventKey == 'click-user-info') {
                $userOpenId = $message->FromUserName;
                $userInfo = $userService->get($userOpenId);
                return "你的信息 " . $userInfo['nickname'] . $userInfo['city'] . $userInfo["headimgurl"];
            }
        }

        return json_encode($message);
    }

    if ($message->MsgType == 'text') {
        if ($message->Content == "我的信息") {
            $userOpenId = $message->FromUserName;
            $userInfo = $userService->get($userOpenId);
            return "你的信息 " . $userInfo['nickname'] . $userInfo['city'] . $userInfo["headimgurl"];
        }
        if ($message->Content == "清空菜单") {
            $menuService->destroy();
            return "OK";
        }

        if ($message->Content == "获取菜单") {
            $menus = [];
            try {
                $menus = $menuService->all();
            } catch (\Exception $e) {
                //not exist menus
            }

            if (!$menus) {
                $menuService->add($buttons);
            }
            return json_encode($menus);
        }

        return "收到消息 " . $message->Content;
    }

    return "没有这种消息的处理方式";
});


$response = $app->server->serve();
// 将响应输出
$response->send(); // Laravel 里请使用：return $response;
