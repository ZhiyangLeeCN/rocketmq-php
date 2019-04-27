<?php
/**
 * author: LiZhiYang
 * email: zhiyanglee@foxmail.com
 */

include "../vendor/autoload.php";

use Young\RocketMQ\Remoting\RemotingCommand;
use Young\RocketMQ\Common\Protocol\RequestCode;
use Young\RocketMQ\Remoting\RemotingConnection;

try {

    //connect to name server.
    $conn = new RemotingConnection("127.0.0.1", 9876, 60);
    $cmd = RemotingCommand::createRequestCommand(RequestCode::GET_NAMESRV_CONFIG, null);
    $conn->writeCmd($cmd);
    $response = $conn->readCmd();
    echo $response->getBody();

} catch (Exception $e) {

    echo $e->getTraceAsString();

}