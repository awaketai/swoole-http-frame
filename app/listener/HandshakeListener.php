<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 2020/2/29
 * Time: 6:47 下午
 */

namespace app\listener;


use Firebase\JWT\JWT;
use swo\event\Listener;
use swo\server\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

class HandshakeListener extends Listener
{
    protected $name = 'ws.hand';

    /**
     * websocket握手事件处理Ø
     * @param Server|null $server
     * @param Request|null $request
     * @param Response|null $response
     * @return bool
     */
    public function handler(Server $server= null,Request $request = null,Response $response = null)
    {
        // TODO: Implement handler() method.
        // websocket握手连接算法验证
        var_dump($request);
        $token = $request->header['sec-websocket-protocol'];
        $secWebSocketKey = $request->header['sec-websocket-key'];
        if(empty($secWebSocketKey)){
            $response->end();
            return false;
        }
        $this->check($server,$token,$request->fd);
        $this->handshake($request,$response);
    }

    protected function handshake($request,$response){
        $secWebSocketKey = $request->header['sec-websocket-key'];

        $patten          = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
        if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
            $response->end();
            return false;
        }
        echo $request->header['sec-websocket-key'];
        $key = base64_encode(sha1(
            $request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
            true
        ));

        $headers = [
            'Upgrade'               => 'websocket',
            'Connection'            => 'Upgrade',
            'Sec-WebSocket-Accept'  => $key,
            'Sec-WebSocket-Version' => '13',
        ];

        // WebSocket connection to 'ws://127.0.0.1:9502/'
        // failed: Error during WebSocket handshake:
        // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
        if (isset($request->header['sec-websocket-protocol'])) {
            $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
        }

        foreach ($headers as $key => $val) {
            $response->header($key, $val);
        }

        $response->status(101);
        $response->end();
    }

    public function check($server,$token,$fd){
        $config = $this->app->make('config');
        $key = $config->get('server.jwt.jwt.key');
        $alg = $config->get('server.jwt.jwt.alg');
        $jwt = JWT::decode($token,$key,$alg);
        $useInfo = $jwt->data;
        // 用户信息存储
        $url = $useInfo->serverUrl;
        $server->getRedis()->hSet($key,$useInfo->uid,json_encode(['fd' => $fd,'name' => $useInfo->name]));
        return true;
    }
}