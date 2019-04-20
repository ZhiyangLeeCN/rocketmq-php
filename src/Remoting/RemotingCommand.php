<?php

/**
 * author: LiZhiYang
 * email: zhiyanglee@foxmail.com
 */

namespace Young\RocketMQ\Remoting;

use Young\RocketMQ\Common\IOUtil;
use Young\RocketMQ\Remoting\Protocol\LanguageCode;
use Young\RocketMQ\Remoting\Protocol\SerializeType;

class RemotingCommand
{
    const RPC_TYPE = 0; // 0, REQUEST_COMMAND

    const RPC_ONEWAY = 1; // 0, RPC

    private $code;

    private $language = LanguageCode::PHP;

    private $version = 0;

    private $opaque = 0;

    private $flag = 0;

    private $remark = '';

    private $extFields = [];

    private $customHeader = null;

    private $serializeTypeCurrentRPC = SerializeType::JSON;

    private $body = null;

    public static function createRequestCommand($code, $customHeader)
    {
        $cmd = new static();
        $cmd->setCode($code);
        $cmd->customHeader = $customHeader;
        return $cmd;
    }

    private function headerEncode()
    {
        $this->makeCustomHeaderToNet();

        return json_encode([
            'code'                      =>  $this->code,
            'language'                  =>  $this->language,
            'version'                   =>  $this->version,
            'opaque'                    =>  $this->opaque,
            'flag'                      =>  $this->flag,
            'remark'                    =>  $this->remark,
            'extFields'                 =>  $this->extFields,
            'serializeTypeCurrentRPC'   =>  SerializeType::nameOf($this->serializeTypeCurrentRPC)
        ]);
    }

    public static function getProtocolType($source)
    {
        return ($source >> 24) & 0xFF;
    }

    public static function markProtocolType($source, $serializeType)
    {
        $result = IOUtil::uChar($serializeType);
        $result .= IOUtil::uChar(($source >> 16) & 0xFF);
        $result .= IOUtil::uChar(($source >> 8) & 0xFF);
        $result .= IOUtil::uChar(($source) & 0xFF);

        return $result;
    }

    public static function getHeaderLength($length)
    {
        return $length & 0xFFFFFF;
    }

    public function markResponseType()
    {
        $bits = 1 << self::RPC_TYPE;
        $this->flag |= $bits;
    }

    public function markOnewayRPC()
    {
        $bits = 1 << self::RPC_ONEWAY;
        $this->flag |= $bits;
    }

    /**
     * @throws \ReflectionException
     */
    public function makeCustomHeaderToNet()
    {
        if (!is_null($this->customHeader)) {

            try {
                $reflectionClass = new \ReflectionClass(get_class($this->customHeader));
                $objectHeader = $reflectionClass->newInstance();
                $fields = $reflectionClass->getProperties();
                foreach ($fields as $field) {
                    if (!$field->isStatic()) {

                        $fieldName = $field->getName();
                        $startsWith = strpos($fieldName, 'this');
                        if ($startsWith === false || $startsWith == 0) {
                            $field->setAccessible(true);
                            $this->extFields[$fieldName] = $field->getValue($objectHeader);
                        }

                    }
                }

            } catch (\ReflectionException $e) {

                throw $e;

            }

        }
    }

    public static function decode($byteBuff)
    {
        $length = IOUtil::readUInt32($byteBuff, 0);
        $oriHeaderLen = IOUtil::readInt32($byteBuff, 4);
        $headerLength = self::getHeaderLength($oriHeaderLen);

        $headerData = substr($byteBuff, 8, $headerLength);
        $header = json_decode($headerData, true);
        $cmd = new static();
        $cmd->code = $header['code'];
        $cmd->language = $header['language'];
        $cmd->version = $header['version'];
        $cmd->opaque = $header['opaque'];
        $cmd->flag = $header['flag'];
        $cmd->remark = $header['remark'];
        $cmd->extFields = $header['extFields'];
        $cmd->serializeTypeCurrentRPC = SerializeType::valueOf($header['serializeTypeCurrentRPC']);

        $bodyLength = $length - 4 - $headerLength;
        if ($bodyLength > 0) {
            $cmd->body = substr($byteBuff, 8 + $headerLength, $bodyLength);
        }

        return $cmd;
    }

    public function encode()
    {
        // 1> header length size
        $length = 4;

        // 2> header data length
        $headerData = $this->headerEncode();
        $headerLength = strlen($headerData);
        $length += $headerLength;

        // 3> body data length
        if (!is_null($this->body)) {
            $length += strlen($this->body);
        }

        // length
        $result = IOUtil::uInt32($length);

        // header length
        $result .= static::markProtocolType($headerLength, $this->serializeTypeCurrentRPC);

        // header data
        $result .= $headerData;

        // body data
        if (!is_null($this->body)) {
            $result .= $this->body;
        }

        return $result;

    }

    /**
     * @param $classHeader
     * @return object
     * @throws \ReflectionException
     */
    public function decodeCommandCustomHeader($classHeader)
    {
        try {

            $reflectionClass = new \ReflectionClass($classHeader);
            $objectHeader = $reflectionClass->newInstance();
            $fields = $reflectionClass->getProperties();
            foreach ($fields as $field) {
                if (!$field->isStatic()) {

                    $fieldName = $field->getName();
                    $startsWith = strpos($fieldName, 'this');
                    if ($startsWith === false || $startsWith == 0) {
                        $field->setAccessible(true);
                        if (isset($this->extFields[$fieldName])) {
                            $field->setValue($objectHeader, $this->extFields[$fieldName]);
                        }
                    }

                }
            }

            return $objectHeader;

        } catch (\ReflectionException $e) {

            throw $e;

        }
    }

    public function getType()
    {
        if ($this->isResponseType()) {
            return RemotingCommandType::RESPONSE_COMMAND;
        } else {
            return RemotingCommandType::REQUEST_COMMAND;
        }
    }

    public function isResponseType()
    {
        $bits = 1 << self::RPC_TYPE;
        return ($this->flag & $bits) == $bits;
    }

    public function isOnewayRPC()
    {
        $bits = 1 << self::RPC_ONEWAY;
        return ($this->flag & $bits) == $bits;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

}