<?php

    namespace MemcacheTool\Websocket;

    require_once __DIR__ . '/../../lib/MemcachedHelper.php';

    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;
    use MemcacheTool\Console\Helper\MemcachedHelper;

    class MemcacheInfo implements MessageComponentInterface{
        protected $clients;
        private $memcacheAddress;
        private $memcachePort;

        public function __construct($memcacheAddress, $memcachePort) {
            $this->memcacheAddress = $memcacheAddress;
            $this->memcachePort = $memcachePort;
            $this->clients = new \SplObjectStorage;
        }

        public function onOpen(ConnectionInterface $conn) {
            $this->clients->attach($conn);
        }

        public function onMessage(ConnectionInterface $from, $msg) {
            $memcachedHelper = new MemcachedHelper($this->memcacheAddress, $this->memcachePort);

            if($this->clients->contains($from)) {
                $msg = json_decode($msg, true);
                $action = isset($msg['action']) && !empty($msg['action']) ? $msg['action'] : '';

                switch($action) {
                    case 'get_memcache_data': {
                        $msg['action'] = $action;
                        $msg['data'] = $memcachedHelper->getMemcacheData(null, null, false, true);
                        $data = json_encode($msg);
                        $from->send($data);
                        break;
                    }
                    case 'get_memcache_key_info': {
                        $msg['action'] = $action;
                        $msg['data'] = $memcachedHelper->getMemcacheData(array($msg['key']));
                        $data = json_encode($msg);
                        $from->send($data);
                        break;
                    }
                    case 'flush_key': {
                        $msg['action'] = $action;
                        $msg['data'] = $memcachedHelper->flushMemcacheData(array($msg['key']));
                        $data = json_encode($msg);
                        $from->send($data);
                        break;
                    }
                    case 'flush_all': {
                        $msg['action'] = $action;
                        $msg['data'] = $memcachedHelper->flushMemcacheData();
                        $data = json_encode($msg);
                        $from->send($data);
                        break;
                    }
                }
            }
        }

        public function onClose(ConnectionInterface $conn) {
            $this->clients->detach($conn);
        }

        public function onError(ConnectionInterface $conn, \Exception $e) {
            echo "An error has occured: {$e->getMessage()}\n";
            $conn->close();
        }
    }
