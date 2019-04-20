<?php
/**
 * author: LiZhiYang
 * email: zhiyanglee@foxmail.com
 */

namespace Young\RocketMQ\Remoting\Protocol;


class SerializeType
{
    const JSON = 0;

    const ROCKETMQ = 1;

    public static function nameOf($type)
    {
        switch ($type) {
            case self::JSON:
                return 'JSON';
            case self::ROCKETMQ:
                return 'ROCKETMQ';
        }

        return null;
    }

    public static function valueOf($type)
    {
        switch ($type) {
            case "JSON":
                return self::JSON;
            case "ROCKETMQ":
                return self::ROCKETMQ;
        }

        return null;
    }
}