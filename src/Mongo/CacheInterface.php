<?php
namespace Mongo;

interface CacheInterface
{
    public function set($key, $value, $millis = null);

    public function get($key);
}
