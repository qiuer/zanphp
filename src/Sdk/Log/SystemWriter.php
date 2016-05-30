<?php
/**
 * Created by IntelliJ IDEA.
 * User: nuomi
 * Date: 16/5/27
 * Time: 下午3:36
 */

namespace Zan\Framework\Sdk\Log;

use Zan\Framework\Contract\Network\Connection;
use Zan\Framework\Foundation\Contract\Async;
use Zan\Framework\Foundation\Exception\System\InvalidArgumentException;

class SystemWriter implements LogWriter, Async
{
    private $conn;
    private $callback;

    public function __construct($conn)
    {
        if (!$conn instanceof Connection) {
            throw new InvalidArgumentException('$conn master be instanceof Connection.');
            return;
        }
        $this->conn = $conn;
    }

    public function write($log)
    {
        var_dump('SystemWriter', $log, $this->conn);
        $this->conn->setClientCb([$this, 'ioReady']);
        yield $this->conn->send($log);
    }

    public function execute(callable $callback)
    {
        $this->callback = $callback;
    }

    public function ioReady($data)
    {
        var_dump($data);
        $resp = [];
        $exception = null;
        call_user_func_array($this->callback, [$resp, $exception]);
    }

}
