<?php
/**
 * author: LiZhiYang
 * email: zhiyanglee@foxmail.com
 */

namespace Young\RocketMQ\Remoting;

use Young\RocketMQ\Common\Exception\IOException;
use Young\RocketMQ\Common\Exception\RemotingConnectException;

class RemotingConnection
{
    private $host;

    private $port;

    private $conn = null;

    private $connectTimeoutSec;

    /**
     * RemotingConnection constructor.
     *
     * @param string $host
     * @param int $port
     * @param int $connectTimeoutSec
     * @throws RemotingConnectException
     */
    public function __construct($host, $port, $connectTimeoutSec = 60)
    {
        $this->host = $host;
        $this->port = $port;
        $this->connectTimeoutSec = $connectTimeoutSec;
        $this->connect($this->host, $this->port, $this->connectTimeoutSec);
    }

    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @throws RemotingConnectException
     */
    protected function connect($host, $port, $timeout)
    {
        $this->conn = @fsockopen(
            $host,
            $port,
            $errno,
            $errstr,
            $timeout
        );

        if (false === $this->conn) {
            throw new RemotingConnectException("connect:{$host} error[code:{$errno},msg:{$errstr}].");
        }
    }

    public function close()
    {
        if (false !== $this->conn) {
            fclose($this->conn);
        }
    }

    /**
     * @param $len
     * @return string
     * @throws IOException
     */
    protected function read($len)
    {
        $remainingBytes = $len;
        $data = $chunk = '';
        while ($remainingBytes > 0) {
            $chunk = fread($this->conn, $remainingBytes);
            if ($chunk === false) {
                throw new IOException("read from conn error:[read {$len} bytes error].");
            }
            $data .= $chunk;
            $remainingBytes -= strlen($chunk);
        }

        return $data;
    }

    /**
     * @return RemotingCommand
     * @throws IOException
     */
    public function readCmd()
    {
        $lenBuf = $this->read(4);
        $len = unpack('NN', $lenBuf)['N'];

        $headerBuf = $this->read($len);
        return RemotingCommand::decode($headerBuf);
    }

    /**
     * @param RemotingCommand $cmd
     * @return bool|int
     * @throws IOException
     */
    public function writeCmd(RemotingCommand $cmd)
    {
        $byteBuf = $cmd->encode();
        $byteBufLen = strlen($byteBuf);
        $written = 0;

        while ($written < $byteBufLen) {

            $wrote = fwrite($this->conn, substr($byteBuf, $written));
            if ($wrote === false || $wrote === -1) {
                throw new IOException("write to conn error:[written:{$written},byteBufLen:{$byteBufLen}].");
            }

            $written += $wrote;

        }

        return $written;
    }

}