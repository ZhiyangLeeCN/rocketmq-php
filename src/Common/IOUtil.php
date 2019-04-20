<?php
/**
 * author: LiZhiYang
 * email: zhiyanglee@foxmail.com
 */

namespace Young\RocketMQ\Common;


class IOUtil
{
    public static function uInt32($int)
    {
        return pack('N', $int);
    }

    public static function readUInt32($byteBuff, $offset)
    {
        return unpack('NN', $byteBuff, $offset)['N'];
    }

    public static function readInt32($byteBuff, $offset)
    {
        $v = self::readUInt32($byteBuff, $offset);

        if ($v >= 0x80000000) {
            $v -= 0x100000000;
        }

        return $v;
    }

    public static function uChar($char)
    {
        return pack('C', $char);
    }

    public static function readUChar($byteBuff, $offset)
    {
        return unpack('CC', $byteBuff, $offset)['C'];
    }
}