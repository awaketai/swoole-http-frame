<?php
return [
    'http' => [
        'host' => '0,0.0.0',
        'port' => 9000
    ],
    'rpc' => [
        'host' => '127.0.0.1',
        'port' => 9001
    ],
    'websocket' => [
        'host' => '0.0.0.0',
        'port' => 9501
    ],
    'tcp' => [
        'host' => '0,0.0.0',
        'port' => 9501
    ],
    'tcp_enable' => 1, // 是否开启tcp
    'ws' => [

        'is_handshake' => 1, // websocket服务是否自定义握手事件
    ],
    'jwt' => [
        'server' => [
            'host' => '0.0.0.0',
            'port' => 9600,
        ],
        'jwt' => [
            'key' => 'swocloud',
            'alg' => ['HS256']
        ],
    ],
];
