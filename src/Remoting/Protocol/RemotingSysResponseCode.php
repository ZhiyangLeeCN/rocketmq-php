<?php
/**
 * author: LiZhiYang
 * email: zhiyanglee@foxmail.com
 */

namespace Young\RocketMQ\Remoting\Protocol;

class RemotingSysResponseCode
{
    const SUCCESS = 0;

    const SYSTEM_ERROR = 1;

    const SYSTEM_BUSY = 2;

    const REQUEST_CODE_NOT_SUPPORTED = 3;

    const TRANSACTION_FAILED = 4;
}