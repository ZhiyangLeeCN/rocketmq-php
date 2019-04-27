<?php
/**
 * author: LiZhiYang
 * email: zhiyanglee@foxmail.com
 */

namespace Young\RocketMQ\Common\Protocol\Header\Namesrv;


use Young\RocketMQ\Remoting\CommandCustomHeader;

class GetRouteInfoRequestHeader implements CommandCustomHeader
{
    private $topic;

    public function checkFields()
    {

    }

    public function getTopic()
    {
        return $this->topic;
    }

    public function setTopic($topic)
    {
        $this->topic = $topic;
    }
}