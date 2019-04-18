<?php
/**
 * author: LiZhiYang
 * email: zhiyanglee@foxmail.com
 */

class ResponseCode extends RemotingSysResponseCode
{
    const FLUSH_DISK_TIMEOUT = 10;

    const SLAVE_NOT_AVAILABLE = 11;

    const FLUSH_SLAVE_TIMEOUT = 12;

    const MESSAGE_ILLEGAL = 13;

    const SERVICE_NOT_AVAILABLE = 14;

    const VERSION_NOT_SUPPORTED = 15;

    const NO_PERMISSION = 16;

    const TOPIC_NOT_EXIST = 17;
    const TOPIC_EXIST_ALREADY = 18;
    const PULL_NOT_FOUND = 19;

    const PULL_RETRY_IMMEDIATELY = 20;

    const PULL_OFFSET_MOVED = 21;

    const QUERY_NOT_FOUND = 22;

    const SUBSCRIPTION_PARSE_FAILED = 23;

    const SUBSCRIPTION_NOT_EXIST = 24;

    const SUBSCRIPTION_NOT_LATEST = 25;

    const SUBSCRIPTION_GROUP_NOT_EXIST = 26;

    const FILTER_DATA_NOT_EXIST = 27;

    const FILTER_DATA_NOT_LATEST = 28;

    const TRANSACTION_SHOULD_COMMIT = 200;

    const TRANSACTION_SHOULD_ROLLBACK = 201;

    const TRANSACTION_STATE_UNKNOW = 202;

    const TRANSACTION_STATE_GROUP_WRONG = 203;
    const NO_BUYER_ID = 204;

    const NOT_IN_CURRENT_UNIT = 205;

    const CONSUMER_NOT_ONLINE = 206;

    const CONSUME_MSG_TIMEOUT = 207;

    const NO_MESSAGE = 208;
}