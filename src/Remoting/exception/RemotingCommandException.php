<?php
/**
 * author: LiZhiYang
 * email: zhiyanglee@foxmail.com
 */

namespace Young\RocketMQ\Remoting\exception;


use Throwable;

class RemotingCommandException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}