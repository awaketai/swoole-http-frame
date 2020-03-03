<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 2020/2/24
 * Time: 11:31 上午
 */

namespace app\websocket\controllers;

use \Swoole\WebSocket\Server as SwooleWebSocketServer;
class BaseController
{
    public function open(SwooleWebSocketServer $ws,$request){

        var_dump($request->fd, $request->get, $request->server);
        $ws->push($request->fd, "hello, welcome\n");
        return 'websocket - open';
    }

    public function message(SwooleWebSocketServer $ws,$frame){

        echo "Message: {$frame->data}\n";
        return 'websocket - message';
    }

    public function close(SwooleWebSocketServer $ws,$fd){

        echo "client-{$fd} is closed\n";
        return 'websocket - close';
    }

}