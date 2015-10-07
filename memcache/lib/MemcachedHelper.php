<?php

    namespace MemcacheTool\Console\Helper;

    class MemcachedHelper {
        const MAX_KEYS_SHOW = 50;
        const MAX_VALUE_LENGTH = 50;

        private $memcachedInstance;

        public function __construct($host, $port) {
            $this->memcachedInstance = new \Memcached();
            $this->memcachedInstance->addServer($host, $port);
        }

        public function getMemcacheData($keys = array(), $maxKeysShow = 0, $compact = false, $web = false) {
            $memcacheKeys = array();
            $memcacheData = array();

            if(!empty($keys) && is_array($keys)) {
                foreach($keys as $key) {
                    array_push($memcacheKeys, $key);
                }
            } else {
                $memcacheKeys = $this->memcachedInstance->getAllKeys();
            }

            if(is_array($memcacheKeys) && !empty($memcacheKeys)) {
                $keysCount = count($memcacheKeys);

                if($maxKeysShow > 0 && $maxKeysShow <= $keysCount) {
                    $dataLength = $maxKeysShow;
                } elseif($maxKeysShow > 0 && $maxKeysShow > $keysCount || $web) {
                    $dataLength = $keysCount;
                } elseif($keysCount > self::MAX_KEYS_SHOW) {
                    $dataLength = self::MAX_KEYS_SHOW;
                } else {
                    $dataLength = $keysCount;
                }

                for($i = 0; $i < $dataLength; $i++) {
                    array_push(
                        $memcacheData,
                        array(
                            $memcacheKeys[$i],
                            $this->outputFormat(
                                $this
                                    ->memcachedInstance
                                    ->get($memcacheKeys[$i]),
                                self::MAX_VALUE_LENGTH,
                                $compact
                            )
                        )
                    );
                }
            }

            $this->closeConnect();
            return $memcacheData;
        }

        public function getKeysCount() {
            return count($this->memcachedInstance->getAllKeys());
        }

        public function flushMemcacheData($keys = array()) {
            if(is_array($keys) && !empty($keys)) {
                $flushed = $this->memcachedInstance->deleteMulti($keys);
            } else {
                $flushed = $this->memcachedInstance->flush();
            }

            $this->closeConnect();
            return $flushed;
        }

        private function outputFormat($field, $length, $compact) {
            if(strlen($field) > $length && !$compact) {
                $field = substr($field, 0, $length) . "...";
            }
            return $field;
        }

        private function closeConnect() {
            $this->memcachedInstance->quit();
        }
    }