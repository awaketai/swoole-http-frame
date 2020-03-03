<?php
$address = '0.0.0.0';
$port = 9000;

set_time_limit(0);

$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_set_block($socket); // 阻塞模式
socket_bind($socket,$address,$port);
socket_listen($socket,4);

do{
    echo "Waiting for connection...\n\r";
    $msgsock = socket_accept($socket);
    echo "Waiting for Request...\n\r";
    $buf = socket_read($msgsock,8192);
    $response = hand_shake($buf);
    socket_write($msgsock,$response,strlen($response)); // 发送响应
    $buf = socket_read($msgsock,8192);
    $msg = "Welcome \n";
    $msg = code($msg);
    socket_write($msgsock,$msg,strlen($msg));
    sleep(3);
    $msg = true;
    $msg = code($msg);
    socket_write($msgsock,$msg,strlen($msg));
    sleep(1);
    socket_close($msgsock);
}while(true);

function hand_shake($buf){
    $buf = substr($buf,strpos($buf,'Sec-WebSocket-Key:')+18);
    $key = trim(substr($buf,0,strpos($buf,"\r\n")));
    $newKey = base64_encode(sha1($key.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11',true));
    $new_message = "HTTP/1.1 101 Switching Protocols\r\n";
    $new_message .= "Upgrade: websocket\r\n";
    $new_message .= "Sec-WebSocket-Version: 13\r\n";
    $new_message .= "Connection: Upgrade\r\n";
    $new_message .= "Sec-WebSocket-Accept: " . $newKey . "\r\n\r\n";
    return $new_message;
}

function code($msg){
    $msg = preg_replace(array('/\r$/','/\n$/','/\r\n$/',), '', $msg);
    $frame = array();
    $frame[0] = '81';
    $len = strlen($msg);
    $frame[1] = $len<16?'0'.dechex($len):dechex($len);
    $frame[2] = ord_hex($msg);
    $data = implode('',$frame);
    return pack("H*", $data);
}

function ord_hex($data)  {
    $msg = '';
    $l = strlen($data);
    for ($i= 0; $i<$l; $i++) {
        $msg .= dechex(ord($data{$i}));
    }
    return $msg;
}

