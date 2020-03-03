<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 2020/2/28
 * Time: 2:39 下午
 */

namespace app\listener;


use swo\event\Listener;
use swo\server\Server;

class StartListener extends Listener
{
    protected $name = 'start';

    /**
     * swoole协程http客户端
     *
     */
    public function handler(Server $server = null){
        go(function () use ($server){
            // 协程http客户端
            $cli = new \Swoole\Coroutine\Http\Client('132.232.5.86',9500);
            // 升级为websocket
            if($cli->upgrade("/")){
                $data = [
                    'ip' => '132.232.5.86',
                    'port' => 9501,
                    'serverName' => 'swo_im1',
                    'method' => 'register'
                ];
                $cli->push(json_encode($data));
                // 使用定时器定时推送信息，保持长连接
                \Swoole\Timer::tick(3000,function() use ($cli){
                    $cli->push('',WEBSOCKET_OPCODE_PING);
                });
            }
//            $cli->close();
        });
    }
}